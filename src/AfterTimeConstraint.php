<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;

class AfterTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $startDate)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        $sequence = new Sequence(Period::fromDate($this->startDate, INF));
        return $this->clampSequence($sequence, $clampPeriod);
    }
}