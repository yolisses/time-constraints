<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\BeforeTimeConstraint;

class BeforeTimeConstraintTest extends TestCase
{
    public function testBeforeTimeConstraintBefore()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new BeforeTimeConstraint($instant);

        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals([new TimePeriod($start_instant, $end_instant)], $periods);
    }

    public function testBeforeTimeConstraintDuring()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new BeforeTimeConstraint($instant);

        $clampPeriod = Period::fromDate('2025-01-02', '2025-01-04');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals([new TimePeriod($start_instant, $instant)], $periods);
    }

    public function testBeforeTimeConstraintAfter()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new BeforeTimeConstraint($instant);

        $clampPeriod = Period::fromDate('2025-01-04', '2025-01-05');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals([], $periods);
    }
}