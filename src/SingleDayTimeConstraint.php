<?php

namespace Yolisses\TimeConstraints;

use League\Period\Bounds;
use League\Period\Period;
use Yolisses\TimeConstraints\Period\TimePeriod;

class SingleDayTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $day)
    {
    }

    public function getPeriods(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        $start = new \DateTimeImmutable($this->day->format('Y-m-d'));
        $end = (new \DateTimeImmutable($this->day->format('Y-m-d')))->modify('+1 day');

        $period = Period::fromDate($start, $end, Bounds::IncludeStartExcludeEnd);
        $periods = [new TimePeriod($start, $end)];

        return $this->clampPeriods($periods, $start_instant, $end_instant);
    }
}