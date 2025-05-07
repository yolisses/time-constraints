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
     * @param TimeConstraint[] $timeConstraints
     */
    public function __construct(public array $timeConstraints)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        $sequences = array_map(function (TimeConstraint $timeConstraint) use ($clampPeriod) {
            return $timeConstraint->getSequence($clampPeriod);
        }, $this->timeConstraints);
        return SequencesIntersection::intersection($sequences);
    }
}