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
            $newSequence = $time_constraint->getSequence($clampPeriod);
            foreach ($newSequence as $period) {
                $periods[] = $period;
            }
        }

        return (new Sequence(...$periods))->unions();
    }
}