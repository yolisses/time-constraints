<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\TimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriodOperations;

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

    public function getPeriods(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        if (empty($this->time_constraints)) {
            return [];
        }

        $periods = $this->time_constraints[0]->getPeriods($start_instant, $end_instant);

        foreach ($this->time_constraints as $time_constraint) {
            $periods = TimePeriodOperations::intersection(
                $periods,
                $time_constraint->getPeriods($start_instant, $end_instant)
            );
        }

        return $periods;
    }
}