<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class SingleIntervalTimeConstraint extends TimeConstraint
{
    public function __construct(public TimeInterval $time_interval)
    {
    }

    public function getIntervals(\DateTime $start_instant, \DateTime $end_instant): array
    {
        $intervals = [$this->time_interval];

        return $this->clampIntervals($intervals, $start_instant, $end_instant);
    }
}