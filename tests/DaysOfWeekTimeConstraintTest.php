<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\DaysOfWeekTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class DaysOfWeekTimeConstraintTest extends TestCase
{
    function testGetIntervals()
    {
        $days_of_week = [
            1, // Monday
            3, // Wednesday
            6, // Saturday
        ];
        $constraint = new DaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTimeImmutable('2025-01-01 02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-09 05:06:07'); // Thursday

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-01 02:03:04', '2025-01-02'),
            TimeInterval::fromStrings('2025-01-04', '2025-01-05'),
            TimeInterval::fromStrings('2025-01-06', '2025-01-07'),
            TimeInterval::fromStrings('2025-01-08', '2025-01-09'),
        ], $intervals);
    }


    public function testGetIntervals2()
    {
        $days_of_week = [
            1, // Monday
            2, // Tuesday
            3, // Wednesday
            5, // Friday
        ];

        $constraint = new DaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTimeImmutable('2025-01-01 08:00:00'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-10 17:00:00'); // Friday

        $intervals = $constraint->getIntervals($start_instant, $end_instant);

        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-01 08:00:00', '2025-01-02 00:00:00'), // Wednesday
            TimeInterval::fromStrings('2025-01-03 00:00:00', '2025-01-04 00:00:00'), // Friday
            TimeInterval::fromStrings('2025-01-06 00:00:00', '2025-01-09 00:00:00'), // Monday, Tuesday, Wednesday
            TimeInterval::fromStrings('2025-01-10 00:00:00', '2025-01-10 17:00:00'), // Friday
        ], $intervals);
    }

    function testGetIntervalsEmptyDaysOfWeek()
    {
        $days_of_week = [];
        $constraint = new DaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTimeImmutable('2025-01-01 02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-09 05:06:07'); // Thursday

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }

    function testGetIntervalsEmptyResult()
    {
        $days_of_week = [
            1, // Monday
            2, // Tuesday
        ];
        $constraint = new DaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTimeImmutable('2025-01-01 02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-05 05:06:07'); // Sunday

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }
}