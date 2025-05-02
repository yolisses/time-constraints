<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Period\TimePeriod;

class SinglePeriodTimeConstraint extends TimeConstraint
{
    public function __construct(public TimePeriod $time_period)
    {
    }

    public static function fromStrings(string $start_instant, string $end_instant): self
    {
        return new self(TimePeriod::fromStrings($start_instant, $end_instant));
    }

    public function getSequence(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): Sequence
    {
        $periods = [$this->time_period];

        return $this->clampPeriods($periods, $start_instant, $end_instant);
    }
}