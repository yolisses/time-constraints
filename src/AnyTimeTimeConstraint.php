<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Period\TimePeriod;

class AnyTimeTimeConstraint extends TimeConstraint
{
    public function __construct()
    {
    }

    public function getPeriods(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        return [new TimePeriod(clone $start_instant, clone $end_instant),];
    }
}