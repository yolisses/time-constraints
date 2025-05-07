<?php

namespace Yolisses\TimeConstraints;

use League\Period\Bounds;
use League\Period\Period;
use League\Period\Sequence;
use League\Period\UnprocessableInterval;

enum OnZeroDuration
{
    case THROW_EXCEPTION;
    case GET_CLOSEST_PAST;
    case GET_CLOSEST_FUTURE;
}

abstract class TimeConstraint
{

    // The current way of getting a sequence is error-prone because it depends
    // on using clampSequence in all getSequence implementations. One possible
    // way to make it more robust is to create a method getRawPeriods, which
    // returns the periods unclamped, and call it inside a default
    // implementation of getSequence. If performance is an issue for some cases,
    // it will still be possible to implement clamping while getting the periods
    // and override getSequence to simply return them.

    /**
     * Returns the periods that satisfy the constraint between the given
     * instants.
     */
    abstract public function getSequence(Period $clampPeriod): Sequence;


    public static function clampSequence(Sequence $sequence, Period $clamp_period): Sequence
    {
        $periods = $sequence->toList();
        $intersectingPeriods = [];
        foreach ($periods as $period) {
            try {
                $intersection = $period->intersect($clamp_period);
                $intersectingPeriods[] = $intersection;
            } catch (UnprocessableInterval $e) {
                // Skip periods that do not overlap
                continue;
            }
        }
        return new Sequence(...$intersectingPeriods);
    }

    private function getSearchPeriod(\DateTimeImmutable $searchStartDate, \DateTimeImmutable $searchEndDate): Period
    {
        if ($searchStartDate > $searchEndDate) {
            return Period::fromDate($searchEndDate, $searchStartDate, Bounds::ExcludeStartIncludeEnd);
        }
        return Period::fromDate($searchStartDate, $searchEndDate, Bounds::IncludeStartExcludeEnd);
    }

    /**
     * @return Period[]
     */
    private function getPeriodsAllowingReverse(\DateTimeImmutable $searchStartDate, \DateTimeImmutable $searchEndDate): array
    {
        $searchPeriod = $this->getSearchPeriod($searchStartDate, $searchEndDate);
        $sequence = $this->getSequence($searchPeriod);

        /**
         * @var Period[]
         */
        $periods = [];

        foreach ($sequence as $period) {
            $periods[] = $period;
        }

        $shouldReverse = $searchStartDate > $searchEndDate;
        usort($periods, function (Period $a, Period $b) use ($shouldReverse) {
            return $shouldReverse ? $b->startDate <=> $a->startDate : $a->startDate <=> $b->startDate;
        });

        return $periods;
    }

    /**
     * Returns the closest instant that satisfies the constraint. Because the
     * time constraints details are unknown, theres no guarantee that the
     * instant exists at all. To deal with that, the search period is moved
     * forward iteratively. If the max number of iterations is reached, an
     * Exception is thrown.
     * @param \DateTimeImmutable $startDate The instant to start the search.
     * @param int $search_period_duration The duration in seconds used in the
     * search period.
     * @param int $max_iterations The maximum number of iterations to search the
     * instant.
     * @throws \Exception
     * @return \DateTimeImmutable
     */
    public function getClosestInstant(
        \DateTimeImmutable $startDate,
        int $search_period_duration,
        int $max_iterations = 1000,
    ): \DateTimeImmutable {
        $currentStart = $startDate;
        $iterations = 0;

        while ($iterations < $max_iterations) {
            // Create a search period starting from currentStart with the given duration
            $endDate = $currentStart->modify("+{$search_period_duration} seconds");
            $searchPeriod = Period::fromDate($currentStart, $endDate, Bounds::IncludeAll);

            // Get the sequence of periods that satisfy the constraint within the search period
            $sequence = $this->getSequence($searchPeriod);

            // If the sequence is not empty, find the closest instant
            if (!$sequence->isEmpty()) {
                foreach ($sequence as $period) {
                    // Check if startDate is within or before the period
                    if ($startDate <= $period->endDate) {
                        // If startDate is before the period, return the period's start
                        if ($startDate < $period->startDate) {
                            return $period->startDate;
                        }
                        // If startDate is within the period, return it
                        return $startDate;
                    }
                }
            }

            // Move the search period forward by search_period_duration
            $currentStart = $currentStart->modify("+{$search_period_duration} seconds");
            $iterations++;
        }

        throw new ClosestDateNotReachedError();
    }

    /**
     * Returns the end instant given the start instant and the duration. Because
     * the time constraints details are unknown, theres no guarantee that the
     * end instant exists at all. To deal with that, the end instant is searched
     * using a search period, that is moved forward iteratively. If the max
     * number of iterations is reached, an Exception is thrown.
     *
     * The duration can be negative, which means that the end instant is before
     * the start instant. In this case, the search period duration must be
     * negative.
     *
     * @param \DateTimeImmutable $start_instant
     * @param int $duration The duration in seconds.
     * @param int $max_iterations
     * @param null|int $search_period_duration The duration in seconds used in
     * the search period. This can be increased to deal with time constraints
     * that return more sparse periods. The default value is  `2 * $duration` if
     * on_zero_duration is THROW_EXCEPTION, and 1 day if on_zero_duration is
     * GET_CLOSEST_PAST or GET_CLOSEST_FUTURE.
     * @param OnZeroDuration $on_zero_duration What to do if the duration is 0.
     * @throws \Exception
     * @return \DateTimeImmutable
     */
    public function getEndInstant(
        \DateTimeImmutable $start_instant,
        int $duration,
        int $max_iterations = 1000,
        null|int $search_period_duration = null,
        OnZeroDuration $on_zero_duration = OnZeroDuration::THROW_EXCEPTION,
    ) {
        if ($duration == 0) {
            $one_day_in_seconds = 24 * 60 * 60;
            if ($on_zero_duration === OnZeroDuration::THROW_EXCEPTION) {
                throw new \Exception("Duration must be different from 0");
            } else if ($on_zero_duration === OnZeroDuration::GET_CLOSEST_PAST) {
                return $this->getClosestInstant($start_instant, -$one_day_in_seconds, $max_iterations);
            } else if ($on_zero_duration === OnZeroDuration::GET_CLOSEST_FUTURE) {
                return $this->getClosestInstant($start_instant, $one_day_in_seconds, $max_iterations);
            }
        }

        $is_duration_negative = $duration < 0;

        if ($is_duration_negative && $search_period_duration > 0) {
            throw new \Exception("search_period_duration must be negative if duration is negative");
        }

        if (empty($search_period_duration)) {
            $search_period_duration = $duration * 2;
        }

        $search_start_instant = $start_instant;
        $search_end_instant = $start_instant->modify("$search_period_duration seconds");


        $cumulative_duration = 0;
        for ($i = 0; $i < $max_iterations; $i++) {
            $periods = $this->getPeriodsAllowingReverse($search_start_instant, $search_end_instant);

            // Iterates over periods
            foreach ($periods as $period) {
                if ($cumulative_duration + $period->timeDuration() < abs($duration)) {
                    $cumulative_duration += $period->timeDuration();
                } else {
                    $remaining_duration = abs($duration) - $cumulative_duration;
                    if ($is_duration_negative) {
                        $negative_remaining_duration = -$remaining_duration;
                        return $period->endDate->modify("$negative_remaining_duration seconds");
                    } else {
                        return $period->startDate->modify("$remaining_duration seconds");
                    }

                }
            }

            $search_start_instant = $search_end_instant;
            $search_end_instant = $search_end_instant->modify("$search_period_duration seconds");
        }

        throw new \Exception("End instant not found with max iterations equals $max_iterations");
    }
}