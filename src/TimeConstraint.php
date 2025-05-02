<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Interval\TimeInterval;
use Yolisses\TimeConstraints\Interval\TimeIntervalOperations;

enum OnZeroDuration
{
    case THROW_EXCEPTION;
    case GET_CLOSEST_PAST;
    case GET_CLOSEST_FUTURE;
}

abstract class TimeConstraint
{
    /**
     * Returns the intervals that satisfy the constraint between the given instants.
     * @param \DateTimeImmutable $start_instant
     * @param \DateTimeImmutable $end_instant
     * @return array<TimeInterval>
     */
    abstract public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array;

    public function clampIntervals($intervals, \DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant)
    {
        return TimeIntervalOperations::intersection(
            $intervals,
            [new TimeInterval($start_instant, $end_instant)]
        );
    }

    public function getTotalDuration(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant)
    {
        $intervals = $this->getIntervals($start_instant, $end_instant);

        $total_duration = 0;
        foreach ($intervals as $interval) {
            $total_duration += $interval->getDuration();
        }

        return $total_duration;
    }

    public function getIntervalsAllowingReverse(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant)
    {
        $is_duration_negative = $end_instant < $start_instant;

        if ($is_duration_negative) {
            $intervals = $this->getIntervals($end_instant, $start_instant);
        } else {
            $intervals = $this->getIntervals($start_instant, $end_instant);
        }

        if ($is_duration_negative) {
            return array_reverse($intervals);
        }
        return $intervals;
    }

    /**
     * Returns the closest instant that satisfies the constraint. Because the
     * time constraints details are unknown, theres no guarantee that the
     * instant exists at all. To deal with that, the search interval is moved
     * forward iteratively. If the max number of iterations is reached, an
     * Exception is thrown.
     * @param \DateTimeImmutable $start_instant The instant to start the search.
     * @param int $search_interval_duration The duration in seconds used in the
     * search interval.
     * @param int $max_iterations The maximum number of iterations to search the
     * instant.
     * @throws \Exception
     * @return \DateTimeImmutable
     */
    public function getClosestInstant(
        \DateTimeImmutable $start_instant,
        int $search_interval_duration,
        int $max_iterations = 1000,
    ) {
        $search_start_instant = $start_instant;
        $search_end_instant = $start_instant->modify("$search_interval_duration seconds");

        for ($i = 0; $i < $max_iterations; $i++) {
            $intervals = $this->getIntervalsAllowingReverse($search_start_instant, $search_end_instant);

            // Iterates over intervals
            foreach ($intervals as $interval) {
                if ($interval->getStart() <= $start_instant && $start_instant <= $interval->getEnd()) {
                    return $start_instant;
                } else if ($interval->getStart() > $start_instant) {
                    return $interval->getStart();
                } else if ($interval->getEnd() < $start_instant) {
                    return $interval->getEnd();
                }
            }

            $search_start_instant = $search_end_instant;
            $search_end_instant = $search_end_instant->modify("$search_interval_duration seconds");
        }

        throw new \Exception("Closest instant not found with max iterations equals $max_iterations");
    }

    /**
     * Returns the end instant given the start instant and the duration. Because
     * the time constraints details are unknown, theres no guarantee that the
     * end instant exists at all. To deal with that, the end instant is searched
     * using a search interval, that is moved forward iteratively. If the max
     * number of iterations is reached, an Exception is thrown.
     *
     * The duration can be negative, which means that the end instant is before
     * the start instant. In this case, the search interval duration must be
     * negative.
     *
     * @param \DateTimeImmutable $start_instant
     * @param int $duration The duration in seconds.
     * @param int $max_iterations
     * @param null|int $search_interval_duration The duration in seconds used in
     * the search interval. This can be increased to deal with time constraints
     * that return more sparse intervals. The default value is  `2 * $duration`
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
        null|int $search_interval_duration = null,
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

        if ($is_duration_negative && $search_interval_duration > 0) {
            throw new \Exception("search_interval_duration must be negative if duration is negative");
        }

        if (empty($search_interval_duration)) {
            $search_interval_duration = $duration * 2;
        }

        $search_start_instant = $start_instant;
        $search_end_instant = $start_instant->modify("$search_interval_duration seconds");


        $cumulative_duration = 0;
        for ($i = 0; $i < $max_iterations; $i++) {
            $intervals = $this->getIntervalsAllowingReverse($search_start_instant, $search_end_instant);

            // Iterates over intervals
            foreach ($intervals as $interval) {
                if ($cumulative_duration + $interval->getDuration() < abs($duration)) {
                    $cumulative_duration += $interval->getDuration();
                } else {
                    $remaining_duration = abs($duration) - $cumulative_duration;
                    if ($is_duration_negative) {
                        $negative_remaining_duration = -$remaining_duration;
                        return $interval->getEnd()->modify("$negative_remaining_duration seconds");
                    } else {
                        return $interval->getStart()->modify("$remaining_duration seconds");
                    }

                }
            }

            $search_start_instant = $search_end_instant;
            $search_end_instant = $search_end_instant->modify("$search_interval_duration seconds");
        }

        throw new \Exception("End instant not found with max iterations equals $max_iterations");
    }
}