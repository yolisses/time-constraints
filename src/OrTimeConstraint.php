<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeIntervalOperations;

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

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        $intervals = [];

        foreach ($this->time_constraints as $time_constraint) {
            $intervals = array_merge($intervals, $time_constraint->getIntervals($start_instant, $end_instant));
        }

        return TimeIntervalOperations::union($intervals);
    }
}