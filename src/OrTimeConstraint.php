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
     * @param TimeConstraint[] $timeConstraints
     */
    public function __construct(public array $timeConstraints)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        $periods = [];

        foreach ($this->timeConstraints as $timeConstraint) {
            $newSequence = $timeConstraint->getSequence($clampPeriod);
            foreach ($newSequence as $period) {
                $periods[] = $period;
            }
        }

        return (new Sequence(...$periods))->unions();
    }
}