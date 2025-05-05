<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
use Yolisses\TimeConstraints\TimeConstraint;

class SequenceTimeConstraint extends TimeConstraint
{
    public function __construct(public Sequence $sequence)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        return $this->clampSequence($this->sequence, $clampPeriod);
    }
}