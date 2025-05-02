<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\AnyTimeTimeConstraint;
use Yolisses\TimeConstraints\SingleDayTimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriod;

class AnyTimeTimeConstraintTest extends TestCase
{
    public function testGetPeriods()
    {
        $constraint = new AnyTimeTimeConstraint();

        $start_instant = new DateTimeImmutable('2025-01-01 02:03:04');
        $end_instant = new DateTimeImmutable('2025-01-03 05:06:07');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([new TimePeriod($start_instant, $end_instant)], $periods);
    }

    public function testGetPeriods2()
    {
        $day = new DateTimeImmutable('2025-01-02 00:00:00');
        $constraint = new SingleDayTimeConstraint($day);

        $start_instant = new DateTimeImmutable('2025-01-02 02:03:04');
        $end_instant = new DateTimeImmutable('2025-01-02 05:06:07');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([new TimePeriod($start_instant, $end_instant)], $periods);
    }
}