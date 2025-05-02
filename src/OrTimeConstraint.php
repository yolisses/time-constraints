<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
use Yolisses\TimeConstraints\TimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriodOperations;

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

    public function getSequence(Period $clampPeriod): Sequence
    {
        $periods = [];

        foreach ($this->time_constraints as $time_constraint) {
            $periods = array_merge($periods, $time_constraint->getSequence($start_instant, $end_instant));
        }

        return TimePeriodOperations::union($periods);
    }
}