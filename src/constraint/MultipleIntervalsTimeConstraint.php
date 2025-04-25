<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Constraint\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class MultipleIntervalsTimeConstraint extends TimeConstraint
{
    /**
     * @param TimeInterval[] $time_intervals
     */
    public function __construct(public array $time_intervals)
    {
    }

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        return $this->clampIntervals($this->time_intervals, $start_instant, $end_instant);
    }
}