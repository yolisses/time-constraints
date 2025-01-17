<?php

namespace Yolisses\TimeConstraints\Constraint;

use DateTime;
use DateInterval;
use DateTimeImmutable;
use Exception;
use Yolisses\TimeConstraints\Interval\TimeInterval;
use Yolisses\TimeConstraints\Interval\TimeIntervalsIntersection;

abstract class TimeConstraint
{
    /**
     * Returns the intervals that satisfy the constraint between the given instants.
     * @param DateTime $start_instant
     * @param DateTime $end_instant
     * @return array<TimeInterval>
     */
    abstract public function getIntervals(DateTime $start_instant, DateTime $end_instant): array;

    public function clampIntervals($intervals, DateTime $start_instant, DateTime $end_instant)
    {
        return TimeIntervalsIntersection::intersectionTimeIntervals(
            $intervals,
            [new TimeInterval($start_instant, $end_instant)]
        );
    }

    public function getTotalDuration(DateTime $start_instant, DateTime $end_instant)
    {
        $intervals = $this->getIntervals($start_instant, $end_instant);

        $total_duration = 0;
        foreach ($intervals as $interval) {
            $total_duration += $interval->getDuration();
        }

        return $total_duration;
    }

    public function getEndInstant(DateTime $start_instant, int $duration, int $max_iterations = 1000)
    {
        $total_duration = 0;

        $search_start_instant = DateTimeImmutable::createFromMutable($start_instant);
        $search_end_instant = DateTimeImmutable::createFromMutable($start_instant);
        $search_end_instant = $search_end_instant->modify("+$duration seconds");

        for ($i = 0; $i < $max_iterations; $i++) {

            print_r("----------------");
            print_r($search_start_instant);
            print_r($search_end_instant);

            $intervals = $this->getIntervals(
                DateTime::createFromImmutable($search_start_instant),
                DateTime::createFromImmutable($search_end_instant),
            );

            print_r($intervals);

            foreach ($intervals as $interval) {
                if ($total_duration + $interval->getDuration() >= $duration) {
                    $remaining_duration = $duration - $total_duration;
                    $end_instant = clone $interval->start;
                    $end_instant->add(new DateInterval('PT' . $remaining_duration . 'S'));
                    return $end_instant;
                }
                $total_duration += $interval->getDuration();
            }

            $search_start_instant = $search_end_instant;
            $search_end_instant = $search_end_instant->modify("+$duration seconds");
        }

        throw new Exception("End instant not found with max iterations equals $max_iterations");
    }
}