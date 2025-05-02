<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
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

    public function getSequence(Period $clampPeriod): Sequence
    {
        if (empty($this->time_constraints)) {
            return [];
        }

        $periods = $this->time_constraints[0]->getSequence($start_instant, $end_instant);

        foreach ($this->time_constraints as $time_constraint) {
            $periods = TimePeriodOperations::intersection(
                $periods,
                $time_constraint->getSequence($start_instant, $end_instant)
            );
        }

        return $periods;
    }
}