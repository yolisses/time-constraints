<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;
use Yolisses\TimeConstraints\Interval\TimeIntervalsIntersection;

abstract class TimeConstraint
{
    /**
     * Returns the intervals that satisfy the constraint between the given instants.
     * @param \DateTime $start_instant
     * @param \DateTime $end_instant
     * @return array<TimeInterval>
     */
    abstract public function getIntervals(\DateTime $start_instant, \DateTime $end_instant): array;

    public function clampIntervals($intervals, \DateTime $start_instant, \DateTime $end_instant)
    {
        return TimeIntervalsIntersection::intersectionTimeIntervals(
            $intervals,
            [new TimeInterval($start_instant, $end_instant)]
        );
    }

    public function getTotalDuration(\DateTime $start_instant, \DateTime $end_instant)
    {
        $intervals = $this->getIntervals($start_instant, $end_instant);

        $total_duration = 0;
        foreach ($intervals as $interval) {
            $total_duration += $interval->getDuration();
        }

        return $total_duration;
    }

    /**
     * Returns the end instant given the start instant and the duration. Because
     * the time constraints details are unknown, theres no guarantee that the
     * end instant exists at all. To deal with that, the end instant is searched
     * using a search interval, that is moved forward iteratively. If the max
     * number of iterations is reached, an Exception is thrown.
     * @param \DateTime $start_instant
     * @param int $duration
     * @param int $max_iterations
     * @param null|int $search_interval_duration The duration in seconds used in
     * the search interval. This can be increased to deal with time constraints
     * that return more sparse intervals. If not provided, `2 * $duration` is
     * used. This default value comes from the rough approximation that with no
     * additional information a random instant has a change of one half of
     * satisfying the time constraint.
     * @throws \Exception
     * @return \DateTime
     */
    public function getEndInstant(
        \DateTime $start_instant,
        int $duration,
        int $max_iterations = 1000,
        null|int $search_interval_duration = null,
    ) {
        if (empty($search_interval_duration)) {
            $search_interval_duration = $duration * 2;
        }

        $search_start_instant = \DateTimeImmutable::createFromMutable($start_instant);
        $search_end_instant = \DateTimeImmutable::createFromMutable($start_instant)->modify("+$search_interval_duration seconds");

        $total_duration = 0;
        for ($i = 0; $i < $max_iterations; $i++) {
            $intervals = $this->getIntervals(
                \DateTime::createFromImmutable($search_start_instant),
                \DateTime::createFromImmutable($search_end_instant),
            );

            foreach ($intervals as $interval) {
                if ($total_duration + $interval->getDuration() >= $duration) {
                    $remaining_duration = $duration - $total_duration;
                    $end_instant = clone $interval->start;
                    $end_instant->modify("+$remaining_duration seconds");
                    return $end_instant;
                }
                $total_duration += $interval->getDuration();
            }

            $search_start_instant = $search_end_instant;
            $search_end_instant = $search_end_instant->modify("+$search_interval_duration seconds");
        }

        throw new \Exception("End instant not found with max iterations equals $max_iterations");
    }
}