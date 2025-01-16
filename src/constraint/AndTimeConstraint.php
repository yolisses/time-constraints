<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Constraint\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeIntervalsIntersection;

/**
 * Apply a logical AND between time constraints.
 */
class AndTimeConstraint extends TimeConstraint
{
    /**
     * @param array<TimeConstraint> $time_constraints
     */
    public function __construct(public array $time_constraints)
    {
    }

    public function getIntervals(\DateTime $start_instant, \DateTime $end_instant): array
    {
        $intervals = [];

        foreach ($this->time_constraints as $time_constraint) {
            $intervals = TimeIntervalsIntersection::intersectionTimeIntervals(
                $intervals,
                $time_constraint->getIntervals($start_instant, $end_instant)
            );
        }

        return $intervals;
    }
}