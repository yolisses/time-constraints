<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
use Yolisses\TimeConstraints\TimeConstraint;

/**
 * Apply a difference operation between time constraints A and B.
 */
class ExceptTimeConstraint extends TimeConstraint
{
    /**
     * @param TimeConstraint[] $timeConstraints
     */
    public function __construct(
        public TimeConstraint $timeConstraint1,
        public TimeConstraint $timeConstraint2,
    ) {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        $sequence1 = $this->timeConstraint1->getSequence($clampPeriod);
        $sequence2 = $this->timeConstraint2->getSequence($clampPeriod);
        return $sequence1->subtract($sequence2);
    }
}