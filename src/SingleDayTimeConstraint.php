<?php

namespace Yolisses\TimeConstraints;

use League\Period\Bounds;
use League\Period\Period;
use League\Period\Sequence;

class SingleDayTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $day)
    {
    }

    public function getSequence(Period $clamp_period): Sequence
    {
        $start = new \DateTimeImmutable($this->day->format('Y-m-d'));
        $end = (new \DateTimeImmutable($this->day->format('Y-m-d')))->modify('+1 day');

        $period = Period::fromDate($start, $end, Bounds::IncludeStartExcludeEnd);
        $sequence = new Sequence($period);

        return $this->clampSequence($sequence, $clamp_period);
    }
}