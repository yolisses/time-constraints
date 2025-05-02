<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class SingleIntervalTimeConstraint extends TimeConstraint
{
    public function __construct(public TimeInterval $time_interval)
    {
    }

    public static function fromStrings(string $start_instant, string $end_instant): self
    {
        return new self(TimeInterval::fromStrings($start_instant, $end_instant));
    }

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        $intervals = [$this->time_interval];

        return $this->clampIntervals($intervals, $start_instant, $end_instant);
    }
}