<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;

class SinglePeriodTimeConstraint extends TimeConstraint
{
    public function __construct(public Period $period)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        $sequence = new Sequence($this->period);

        return $this->clampSequence($sequence, $clampPeriod);
    }
}