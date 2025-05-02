<?php

namespace Yolisses\TimeConstraints;

use League\Period\Bounds;
use League\Period\Period;
use League\Period\Sequence;
use League\Period\UnprocessableInterval;

class BeforeTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $endDate, public bool $isEndIncluded)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        if ($clampPeriod->isAfter($this->endDate)) {
            return new Sequence();
        }

        try {
            $idealBounds = $this->isEndIncluded ? Bounds::IncludeAll : Bounds::IncludeStartExcludeEnd;
            $idealPeriod = Period::fromDate($clampPeriod->startDate, $this->endDate, $idealBounds);
            $period = $idealPeriod->intersect($clampPeriod);
            return new Sequence($period);
        } catch (UnprocessableInterval) {
            return new Sequence();
        }
    }
}