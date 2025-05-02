<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\TimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriodOperations;

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

    public function getSequence(
        \DateTimeImmutable $start_instant,
        \DateTimeImmutable $end_instant
    ): array {
        $periods_a = $this->time_constraint_a->getSequence($start_instant, $end_instant);
        $periods_b = $this->time_constraint_b->getSequence($start_instant, $end_instant);
        return TimePeriodOperations::difference($periods_a, $periods_b);
    }
}