<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
use Yolisses\TimeConstraints\Period\TimePeriod;

class AnyTimeTimeConstraint extends TimeConstraint
{
    public function __construct()
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        return [new TimePeriod(clone $start_instant, clone $end_instant),];
    }
}