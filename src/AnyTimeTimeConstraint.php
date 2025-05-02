<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class AnyTimeTimeConstraint extends TimeConstraint
{
    public function __construct()
    {
    }

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        return [new TimeInterval(clone $start_instant, clone $end_instant),];
    }
}