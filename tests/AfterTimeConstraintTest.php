<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\AfterTimeConstraint;

class AfterTimeConstraintTest extends TestCase
{
    public function testAfterTimeConstraintBefore()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new AfterTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-01');
        $end_instant = new DateTimeImmutable('2025-01-02');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([], $periods);
    }

    public function testAfterTimeConstraintDuring()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new AfterTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-02');
        $end_instant = new DateTimeImmutable('2025-01-04');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([new TimePeriod($instant, $end_instant)], $periods);
    }

    public function testAfterTimeConstraintAfter()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new AfterTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-04');
        $end_instant = new DateTimeImmutable('2025-01-05');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([new TimePeriod($start_instant, $end_instant)], $periods);
    }
}