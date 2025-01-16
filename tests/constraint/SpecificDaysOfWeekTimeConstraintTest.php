<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\SpecificDaysOfWeekTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class SpecificDaysOfWeekTimeConstraintTest extends TestCase
{
    function testGetIntervals()
    {
        $days_of_week = [
            1, // Monday
            3, // Wednesday
            6, // Saturday
        ];
        $constraint = new SpecificDaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTime('2025-01-01 02:03:04'); // Wednesday
        $end_instant = new DateTime('2025-01-09 05:06:07'); // Thursday

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            new TimeInterval(new DateTime('2025-01-01 02:03:04'), new DateTime('2025-01-02')),
            new TimeInterval(new DateTime('2025-01-04'), new DateTime('2025-01-05')),
            new TimeInterval(new DateTime('2025-01-06'), new DateTime('2025-01-07')),
            new TimeInterval(new DateTime('2025-01-08'), new DateTime('2025-01-09')),
        ], $intervals);
    }

    function testGetIntervalsEmptyDaysOfWeek()
    {
        $days_of_week = [];
        $constraint = new SpecificDaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTime('2025-01-01 02:03:04'); // Wednesday
        $end_instant = new DateTime('2025-01-09 05:06:07'); // Thursday

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }

    function testGetIntervalsEmptyResult()
    {
        $days_of_week = [
            1, // Monday
            2, // Tuesday
        ];
        $constraint = new SpecificDaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTime('2025-01-01 02:03:04'); // Wednesday
        $end_instant = new DateTime('2025-01-05 05:06:07'); // Sunday

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }
}