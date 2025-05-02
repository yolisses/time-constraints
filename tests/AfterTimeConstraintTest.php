<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\AfterTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class AfterTimeConstraintTest extends TestCase
{
    public function testAfterTimeConstraintBefore()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new AfterTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-01');
        $end_instant = new DateTimeImmutable('2025-01-02');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }

    public function testAfterTimeConstraintDuring()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new AfterTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-02');
        $end_instant = new DateTimeImmutable('2025-01-04');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([new TimeInterval($instant, $end_instant)], $intervals);
    }

    public function testAfterTimeConstraintAfter()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new AfterTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-04');
        $end_instant = new DateTimeImmutable('2025-01-05');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([new TimeInterval($start_instant, $end_instant)], $intervals);
    }
}