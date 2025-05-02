<?php

namespace Yolisses\TimeConstraints;

use League\Period\Bounds;
use League\Period\Period;
use League\Period\Sequence;
use League\Period\UnprocessableInterval;

class AfterTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $startDate, public bool $isStartIncluded)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        if ($clampPeriod->isBefore($this->startDate)) {
            return new Sequence();
        }

        try {
            $idealBounds = $this->isStartIncluded ? Bounds::IncludeAll : Bounds::ExcludeStartIncludeEnd;
            $idealPeriod = Period::fromDate($this->startDate, $clampPeriod->endDate, $idealBounds);
            $period = $idealPeriod->intersect($clampPeriod);
            return new Sequence($period);
        } catch (UnprocessableInterval) {
            return new Sequence();
        }
    }
}