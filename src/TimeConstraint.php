<?php

namespace Yolisses\TimeConstraints;

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
    /**
     * Returns the periods that satisfy the constraint between the given instants.
     */
    abstract public function getSequence(Period $clamp_period): Sequence;


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

    public function getTotalDuration(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant)
    {
        $periods = $this->getSequence($start_instant, $end_instant);

        $total_duration = 0;
        foreach ($periods as $period) {
            $total_duration += $period->getDuration();
        }

        return $total_duration;
    }

    public function getPeriodsAllowingReverse(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant)
    {
        $is_duration_negative = $end_instant < $start_instant;

        if ($is_duration_negative) {
            $periods = $this->getSequence($end_instant, $start_instant);
        } else {
            $periods = $this->getSequence($start_instant, $end_instant);
        }

        if ($is_duration_negative) {
            return array_reverse($periods);
        }
        return $periods;
    }

    /**
     * Returns the closest instant that satisfies the constraint. Because the
     * time constraints details are unknown, theres no guarantee that the
     * instant exists at all. To deal with that, the search period is moved
     * forward iteratively. If the max number of iterations is reached, an
     * Exception is thrown.
     * @param \DateTimeImmutable $start_instant The instant to start the search.
     * @param int $search_period_duration The duration in seconds used in the
     * search period.
     * @param int $max_iterations The maximum number of iterations to search the
     * instant.
     * @throws \Exception
     * @return \DateTimeImmutable
     */
    public function getClosestInstant(
        \DateTimeImmutable $start_instant,
        int $search_period_duration,
        int $max_iterations = 1000,
    ) {
        $search_start_instant = $start_instant;
        $search_end_instant = $start_instant->modify("$search_period_duration seconds");

        for ($i = 0; $i < $max_iterations; $i++) {
            $periods = $this->getPeriodsAllowingReverse($search_start_instant, $search_end_instant);

            // Iterates over periods
            foreach ($periods as $period) {
                if ($period->getStart() <= $start_instant && $start_instant <= $period->getEnd()) {
                    return $start_instant;
                } else if ($period->getStart() > $start_instant) {
                    return $period->getStart();
                } else if ($period->getEnd() < $start_instant) {
                    return $period->getEnd();
                }
            }

            $search_start_instant = $search_end_instant;
            $search_end_instant = $search_end_instant->modify("$search_period_duration seconds");
        }

        throw new \Exception("Closest instant not found with max iterations equals $max_iterations");
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
     * that return more sparse periods. The default value is  `2 * $duration`
     * if on_zero_duration is THROW_EXCEPTION, and 1 day if on_zero_duration is
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
                if ($cumulative_duration + $period->getDuration() < abs($duration)) {
                    $cumulative_duration += $period->getDuration();
                } else {
                    $remaining_duration = abs($duration) - $cumulative_duration;
                    if ($is_duration_negative) {
                        $negative_remaining_duration = -$remaining_duration;
                        return $period->getEnd()->modify("$negative_remaining_duration seconds");
                    } else {
                        return $period->getStart()->modify("$remaining_duration seconds");
                    }

                }
            }

            $search_start_instant = $search_end_instant;
            $search_end_instant = $search_end_instant->modify("$search_period_duration seconds");
        }

        throw new \Exception("End instant not found with max iterations equals $max_iterations");
    }
}