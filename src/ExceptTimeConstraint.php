<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Constraint\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeIntervalOperations;

/**
 * Apply a difference operation between time constraints A and B.
 */
class ExceptTimeConstraint extends TimeConstraint
{
    /**
     * @param array<TimeConstraint> $time_constraints
     */
    public function __construct(
        public TimeConstraint $time_constraint_a,
        public TimeConstraint $time_constraint_b,
    ) {
    }

    public function getIntervals(
        \DateTimeImmutable $start_instant,
        \DateTimeImmutable $end_instant
    ): array {
        $intervals_a = $this->time_constraint_a->getIntervals($start_instant, $end_instant);
        $intervals_b = $this->time_constraint_b->getIntervals($start_instant, $end_instant);
        return TimeIntervalOperations::difference($intervals_a, $intervals_b);
    }
}