<?php

namespace Yolisses\TimeConstraints\Constraint;

use DateTime;
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
    abstract public function getIntervals(DateTime $start_instant, DateTime $end_instant): array;

    function clampIntervals($intervals, DateTime $start_instant, DateTime $end_instant)
    {
        return TimeIntervalsIntersection::intersectionTimeIntervals(
            $intervals,
            [new TimeInterval($start_instant, $end_instant)]
        );
    }
}