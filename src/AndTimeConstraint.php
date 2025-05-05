<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
use Yolisses\TimeConstraints\TimeConstraint;

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
        $sequences = array_map(function (TimeConstraint $timeConstraint) use ($clampPeriod) {
            return $timeConstraint->getSequence($clampPeriod);
        }, $this->time_constraints);
        return SequencesUnion::union($sequences);
    }
}