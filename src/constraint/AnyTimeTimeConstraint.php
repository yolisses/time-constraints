<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class AnyTimeTimeConstraint extends TimeConstraint
{
    public function __construct()
    {
    }

    public function getIntervals(\DateTime $start_instant, \DateTime $end_instant): array
    {
        return [new TimeInterval(clone $start_instant, clone $end_instant),];
    }
}