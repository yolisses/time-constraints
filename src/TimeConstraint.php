<?php

namespace Yolisses\TimeConstraints;

use DateTime;
use InvalidArgumentException;
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

    private function getPeriodsForward(\DateTimeImmutable $searchStart, \DateTimeImmutable $searchEnd)
    {
        $searchPeriod = Period::fromDate($searchStart, $searchEnd, Bounds::IncludeStartExcludeEnd);
        $periods = $this->getSequence($searchPeriod)->unions()->toList();
        usort($periods, fn(Period $a, Period $b) => $a->startDate <=> $b->startDate);
        return $periods;
    }

    private function checkClosestDateForward(
        \DateTimeImmutable $targetDate,
        \DateTimeImmutable $searchStart,
        \DateTimeImmutable $searchEnd,
    ) {
        $periods = $this->getPeriodsForward($searchStart, $searchEnd);
        foreach ($periods as $period) {
            if ($targetDate < $period->startDate) {
                return $period->startDate;
            }
            if ($period->startDate == $targetDate || $period->contains($targetDate)) {
                return $targetDate;
            }
        }

        return null;
    }

    private function getPeriodsBackward(\DateTimeImmutable $searchStart, \DateTimeImmutable $searchEnd)
    {
        $searchPeriod = Period::fromDate($searchEnd, $searchStart, Bounds::ExcludeStartIncludeEnd);
        $periods = $this->getSequence($searchPeriod)->unions()->toList();
        usort($periods, fn(Period $a, Period $b) => $b->startDate <=> $a->startDate);
        return $periods;
    }

    private function checkClosestDateBackward(
        \DateTimeImmutable $targetDate,
        \DateTimeImmutable $searchStart,
        \DateTimeImmutable $searchEnd,
    ) {
        $periods = $this->getPeriodsBackward($searchStart, $searchEnd);
        foreach ($periods as $period) {
            if ($targetDate > $period->endDate) {
                return $period->endDate;
            }
            if ($period->endDate == $targetDate || $period->contains($targetDate)) {
                return $targetDate;
            }
        }

        return null;
    }

    public function getClosestDate(
        \DateTimeImmutable $targetDate,
        int $searchPeriodDuration,
        int $maxIterations = 1000,
    ): \DateTimeImmutable {
        if ($searchPeriodDuration == 0) {
            throw new InvalidArgumentException();
        }

        $iterations = 0;
        $searchStart = $targetDate;
        $isReversed = $searchPeriodDuration < 0;
        while ($iterations < $maxIterations) {
            $searchEnd = $searchStart->modify("{$searchPeriodDuration} seconds");

            if ($isReversed) {
                $result = $this->checkClosestDateBackward($targetDate, $searchStart, $searchEnd);
            } else {
                $result = $this->checkClosestDateForward($targetDate, $searchStart, $searchEnd);
            }

            if ($result) {
                return $result;
            }

            $searchStart = $searchStart->modify("{$searchPeriodDuration} seconds");
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
     * @param \DateTimeImmutable $startDate
     * @param int $duration The duration in seconds.
     * @param int $maxIterations
     * @param null|int $searchPeriodDuration The duration in seconds used in
     * the search period. This can be increased to deal with time constraints
     * that return more sparse periods. The default value is  `2 * $duration` if
     * onZeroDuration is THROW_EXCEPTION, and 1 day if onZeroDuration is
     * GET_CLOSEST_PAST or GET_CLOSEST_FUTURE.
     * @param OnZeroDuration $onZeroDuration What to do if the duration is 0.
     * @throws \Exception
     * @return \DateTimeImmutable
     */
    public function getEndInstant(
        \DateTimeImmutable $startDate,
        int $duration,
        int $maxIterations = 1000,
        null|int $searchPeriodDuration = null,
        OnZeroDuration $onZeroDuration = OnZeroDuration::THROW_EXCEPTION,
    ) {
        if ($duration == 0) {
            $one_day_in_seconds = 24 * 60 * 60;
            if ($onZeroDuration === OnZeroDuration::THROW_EXCEPTION) {
                throw new \Exception("Duration must be different from 0");
            } else if ($onZeroDuration === OnZeroDuration::GET_CLOSEST_PAST) {
                return $this->getClosestDate($startDate, -$one_day_in_seconds, $maxIterations);
            } else if ($onZeroDuration === OnZeroDuration::GET_CLOSEST_FUTURE) {
                return $this->getClosestDate($startDate, $one_day_in_seconds, $maxIterations);
            }
        }

        $durationIsNegative = $duration < 0;

        if ($durationIsNegative && $searchPeriodDuration > 0) {
            throw new \Exception("searchPeriodDuration must be negative if duration is negative");
        }

        if (empty($searchPeriodDuration)) {
            $searchPeriodDuration = $duration * 2;
        }

        $searchStart = $startDate;
        $searchEnd = $startDate->modify("$searchPeriodDuration seconds");

        $cumulativeDuration = 0;
        for ($i = 0; $i < $maxIterations; $i++) {
            $periods = $this->getPeriodsAllowingReverse($searchStart, $searchEnd);

            // Iterates over periods
            foreach ($periods as $period) {
                if ($cumulativeDuration + $period->timeDuration() < abs($duration)) {
                    $cumulativeDuration += $period->timeDuration();
                } else {
                    $remainingDuration = abs($duration) - $cumulativeDuration;
                    if ($durationIsNegative) {
                        $negativeRemainingDuration = -$remainingDuration;
                        return $period->endDate->modify("$negativeRemainingDuration seconds");
                    } else {
                        return $period->startDate->modify("$remainingDuration seconds");
                    }

                }
            }

            $searchStart = $searchEnd;
            $searchEnd = $searchEnd->modify("$searchPeriodDuration seconds");
        }

        throw new \Exception("End instant not found with max iterations equals $maxIterations");
    }
}