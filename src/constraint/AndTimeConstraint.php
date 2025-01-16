<?php

use Yolisses\TimeConstraints\Constraint\TimeConstraint;

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

    public function getIntervals(DateTime $start_instant, DateTime $end_instant): array
    {
    }
}