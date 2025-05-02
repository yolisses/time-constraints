<?php

use League\Period\Period;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\AfterTimeConstraint;

class AfterTimeConstraintTest extends TestCase
{
    public function testAfterTimeConstraintBefore()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new AfterTimeConstraint($instant);

        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals([], $sequence);
    }

    public function testAfterTimeConstraintDuring()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new AfterTimeConstraint($instant);

        $clampPeriod = Period::fromDate('2025-01-02', '2025-01-04');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals([new TimePeriod($instant, $end_instant)], $sequence);
    }

    public function testAfterTimeConstraintAfter()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new AfterTimeConstraint($instant);

        $clampPeriod = Period::fromDate('2025-01-04', '2025-01-05');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals([new TimePeriod($start_instant, $end_instant)], $sequence);
    }
}