<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\AnyTimeTimeConstraint;
use Yolisses\TimeConstraints\SingleDayTimeConstraint;

class AnyTimeTimeConstraintTest extends TestCase
{
    public function testGetPeriods()
    {
        $constraint = new AnyTimeTimeConstraint();

        $clampPeriod = Period::fromDate('2025-01-01 02:03:04', '2025-01-03 05:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals([new TimePeriod($start_instant, $end_instant)], $periods);
    }

    public function testGetPeriods2()
    {
        $day = new DateTimeImmutable('2025-01-02 00:00:00');
        $constraint = new SingleDayTimeConstraint($day);

        $clampPeriod = Period::fromDate('2025-01-02 02:03:04', '2025-01-02 05:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals([new TimePeriod($start_instant, $end_instant)], $periods);
    }
}