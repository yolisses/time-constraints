<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Constraint\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeIntervalsUnion;

/**
 * Apply a logical OR between time constraints.
 */
class OrTimeConstraint extends TimeConstraint
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
            $intervals = array_merge($intervals, $time_constraint->getIntervals($start_instant, $end_instant));
        }

        return TimeIntervalsUnion::unionTimeIntervals($intervals);
    }
}