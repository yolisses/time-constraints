<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\SingleDayTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class SingleDayTimeConstraintTest extends TestCase
{
    public function testGetIntervals()
    {
        $day = new DateTimeImmutable('2025-01-02 05:00:00');
        $constraint = new SingleDayTimeConstraint($day);

        $start_instant = new DateTimeImmutable('2025-01-01 02:03:04');
        $end_instant = new DateTimeImmutable('2025-01-03 05:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-02 00:00:00', '2025-01-03 00:00:00'),
        ], $intervals);
    }

    public function testGetIntervals2()
    {
        $day = new DateTimeImmutable('2025-01-02 00:00:00');
        $constraint = new SingleDayTimeConstraint($day);

        $start_instant = new DateTimeImmutable('2025-01-02 02:03:04');
        $end_instant = new DateTimeImmutable('2025-01-02 05:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-02 02:03:04', '2025-01-02 05:06:07'),
        ], $intervals);
    }
}