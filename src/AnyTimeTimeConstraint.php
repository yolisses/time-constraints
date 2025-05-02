<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;

class AnyTimeTimeConstraint extends TimeConstraint
{
    public function __construct()
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        return new Sequence(clone $clampPeriod);
    }
}