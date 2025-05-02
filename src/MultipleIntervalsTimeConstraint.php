<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
use Yolisses\TimeConstraints\TimeConstraint;

class MultiplePeriodsTimeConstraint extends TimeConstraint
{
    /**
     * @param TimePeriod[] $time_periods
     */
    public function __construct(public array $time_periods)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        return $this->clampSequence($this->time_periods, $start_instant, $end_instant);
    }
}