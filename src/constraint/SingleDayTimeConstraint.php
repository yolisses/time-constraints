<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class SingleDayTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $day)
    {
    }

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        $start = new \DateTimeImmutable($this->day->format('Y-m-d'));
        $end = (new \DateTimeImmutable($this->day->format('Y-m-d')))->modify('+1 day');

        $intervals = [new TimeInterval($start, $end),];

        return $this->clampIntervals($intervals, $start_instant, $end_instant);
    }
}