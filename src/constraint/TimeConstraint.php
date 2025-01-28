<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;
use Yolisses\TimeConstraints\Interval\TimeIntervalsIntersection;

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
        return TimeIntervalsIntersection::intersectionTimeIntervals(
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
        $is_duration_negative = $start_instant > $end_instant;

        $params = [$start_instant, $end_instant];
        if ($is_duration_negative) {
            $params = array_reverse($params);
        }

        $intervals = $this->getIntervals(...$params);

        if ($is_duration_negative) {
            return array_reverse($intervals);
        }
        return $intervals;
    }

    /**
     * Returns the end instant given the start instant and the duration. Because
     * the time constraints details are unknown, theres no guarantee that the
     * end instant exists at all. To deal with that, the end instant is searched
     * using a search interval, that is moved forward iteratively. If the max
     * number of iterations is reached, an Exception is thrown.
     * 
     * The duration can be negative, which means that the end instant is before
     * the start instant. In this case, the search interval duration must be negative.
     * 
     * @param \DateTimeImmutable $start_instant
     * @param int $duration The duration in seconds.
     * @param int $max_iterations
     * @param null|int $search_interval_duration The duration in seconds used in
     * the search interval. This can be increased to deal with time constraints
     * that return more sparse intervals. If not provided, `2 * $duration` is
     * used. This default value comes from the rough approximation that with no
     * additional information a random instant has a change of one half of
     * satisfying the time constraint.
     * @throws \Exception
     * @return \DateTimeImmutable
     */
    public function getEndInstant(
        \DateTimeImmutable $start_instant,
        int $duration,
        int $max_iterations = 1000,
        null|int $search_interval_duration = null,
    ) {
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
            // Get intervals

            $intervals = $this->getIntervalsAllowingReverse($search_start_instant, $search_end_instant);

            // Iterates over intervals
            foreach ($intervals as $interval) {
                if ($cumulative_duration + $interval->getDuration() <= abs($duration)) {
                    $cumulative_duration += $interval->getDuration();
                } else {
                    $remaining_duration = abs($duration) - $cumulative_duration;
                    if ($is_duration_negative) {
                        $negative_remaining_duration = -$remaining_duration;
                        return $interval->end->modify("$negative_remaining_duration seconds");
                    } else {
                        return $interval->start->modify("$remaining_duration seconds");
                    }

                }
            }

            $search_start_instant = $search_end_instant;
            $search_end_instant = $search_end_instant->modify("$search_interval_duration seconds");
        }

        throw new \Exception("End instant not found with max iterations equals $max_iterations");
    }
}