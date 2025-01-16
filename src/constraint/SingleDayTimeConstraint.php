<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class SingleDayTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTime $day)
    {
    }

    public function getIntervals(\DateTime $start_instant, \DateTime $end_instant): array
    {
        $start = new \DateTime($this->day->format('Y-m-d'));
        $end = new \DateTime($this->day->format('Y-m-d'));
        $end->modify('+1 day');

        $intervals = [new TimeInterval($start, $end),];

        return $this->clampIntervals($intervals, $start_instant, $end_instant);
    }
}