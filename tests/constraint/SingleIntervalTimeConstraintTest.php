<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\SingleIntervalTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class SingleIntervalTimeConstraintTest extends TestCase
{
    public function testGetIntervals()
    {
        $time_interval = new TimeInterval(
            new DateTimeImmutable('2025-01-02 05:00:00'),
            new DateTimeImmutable('2025-01-02 10:00:00')
        );
        $constraint = new SingleIntervalTimeConstraint($time_interval);

        $start_instant = new DateTimeImmutable('2025-01-01 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-03 09:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-02 05:00:00', '2025-01-02 10:00:00'),
        ], $intervals);
    }

    public function testGetIntervals2()
    {
        $time_interval = new TimeInterval(
            new DateTimeImmutable('2025-01-02 05:00:00'),
            new DateTimeImmutable('2025-01-02 10:00:00')
        );
        $constraint = new SingleIntervalTimeConstraint($time_interval);

        $start_instant = new DateTimeImmutable('2025-01-02 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-02 09:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-02 06:03:04', '2025-01-02 09:06:07'),
        ], $intervals);
    }
}