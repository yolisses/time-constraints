<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\BeforeTimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriod;

class BeforeTimeConstraintTest extends TestCase
{
    public function testBeforeTimeConstraintBefore()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new BeforeTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-01');
        $end_instant = new DateTimeImmutable('2025-01-02');

        $periods = $constraint->getPeriods($start_instant, $end_instant);
        $this->assertEquals([new TimePeriod($start_instant, $end_instant)], $periods);
    }

    public function testBeforeTimeConstraintDuring()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new BeforeTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-02');
        $end_instant = new DateTimeImmutable('2025-01-04');

        $periods = $constraint->getPeriods($start_instant, $end_instant);
        $this->assertEquals([new TimePeriod($start_instant, $instant)], $periods);
    }

    public function testBeforeTimeConstraintAfter()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new BeforeTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-04');
        $end_instant = new DateTimeImmutable('2025-01-05');

        $periods = $constraint->getPeriods($start_instant, $end_instant);
        $this->assertEquals([], $periods);
    }
}