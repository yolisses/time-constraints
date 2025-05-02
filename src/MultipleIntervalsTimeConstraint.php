<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\TimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriod;

class MultiplePeriodsTimeConstraint extends TimeConstraint
{
    /**
     * @param TimePeriod[] $time_periods
     */
    public function __construct(public array $time_periods)
    {
    }

    public function getSequence(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): Sequence
    {
        return $this->clampSequence($this->time_periods, $start_instant, $end_instant);
    }
}