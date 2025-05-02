<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\BeforeTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class BeforeTimeConstraintTest extends TestCase
{
    public function testBeforeTimeConstraintBefore()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new BeforeTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-01');
        $end_instant = new DateTimeImmutable('2025-01-02');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([new TimeInterval($start_instant, $end_instant)], $intervals);
    }

    public function testBeforeTimeConstraintDuring()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new BeforeTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-02');
        $end_instant = new DateTimeImmutable('2025-01-04');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([new TimeInterval($start_instant, $instant)], $intervals);
    }

    public function testBeforeTimeConstraintAfter()
    {
        $instant = new DateTimeImmutable('2025-01-03');
        $constraint = new BeforeTimeConstraint($instant);

        $start_instant = new DateTimeImmutable('2025-01-04');
        $end_instant = new DateTimeImmutable('2025-01-05');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }
}