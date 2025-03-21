<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\TimeOfDayTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class TimeOfDayTimeConstraintTest extends TestCase
{
    function testGetIntervals()
    {
        $time_start = '10:00:00';
        $time_end = '12:00:00';
        $constraint = new TimeOfDayTimeConstraint($time_start, $time_end);

        $start_instant = new DateTimeImmutable('2025-01-01 11:03:04');
        $end_instant = new DateTimeImmutable('2025-01-09 11:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-01 11:03:04', '2025-01-01 12:00'),
            TimeInterval::fromStrings('2025-01-02 10:00', '2025-01-02 12:00'),
            TimeInterval::fromStrings('2025-01-03 10:00', '2025-01-03 12:00'),
            TimeInterval::fromStrings('2025-01-04 10:00', '2025-01-04 12:00'),
            TimeInterval::fromStrings('2025-01-05 10:00', '2025-01-05 12:00'),
            TimeInterval::fromStrings('2025-01-06 10:00', '2025-01-06 12:00'),
            TimeInterval::fromStrings('2025-01-07 10:00', '2025-01-07 12:00'),
            TimeInterval::fromStrings('2025-01-08 10:00', '2025-01-08 12:00'),
            TimeInterval::fromStrings('2025-01-09 10:00', '2025-01-09 11:06:07'),
        ], $intervals);
    }
}