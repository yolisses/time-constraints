<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\WeekdaysTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class WeekdaysTimeConstraintTest extends TestCase
{

    function testIsWeekend()
    {
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTime('2025-01-01'))); // Wednesday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTime('2025-01-02'))); // Thursday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTime('2025-01-03'))); // Friday
        $this->assertTrue(WeekdaysTimeConstraint::getIsWeekend(new DateTime('2025-01-04'))); // Saturday
        $this->assertTrue(WeekdaysTimeConstraint::getIsWeekend(new DateTime('2025-01-05'))); // Sunday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTime('2025-01-06'))); // Monday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTime('2025-01-07'))); // Tuesday
    }

    function testGetIntervals()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTime('2025-01-01T02:03:04'); // Wednesday
        $end_instant = new DateTime('2025-01-30T05:06:07'); // Thursday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            new TimeInterval(new DateTime('2025-01-01T02:03:04'), new DateTime('2025-01-04')),
            new TimeInterval(new DateTime('2025-01-06'), new DateTime('2025-01-11')),
            new TimeInterval(new DateTime('2025-01-13'), new DateTime('2025-01-18')),
            new TimeInterval(new DateTime('2025-01-20'), new DateTime('2025-01-25')),
            new TimeInterval(new DateTime('2025-01-27'), new DateTime('2025-01-30T05:06:07')),
        ], $intervals);
    }

    function testGetIntervalsStartingAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTime('2025-01-04T02:03:04'); // Saturday
        $end_instant = new DateTime('2025-01-30T05:06:07'); // Thursday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            new TimeInterval(new DateTime('2025-01-06'), new DateTime('2025-01-11')),
            new TimeInterval(new DateTime('2025-01-13'), new DateTime('2025-01-18')),
            new TimeInterval(new DateTime('2025-01-20'), new DateTime('2025-01-25')),
            new TimeInterval(new DateTime('2025-01-27'), new DateTime('2025-01-30T05:06:07')),
        ], $intervals);
    }

    function testGetIntervalsEndingAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTime('2025-01-01T02:03:04'); // Wednesday
        $end_instant = new DateTime('2025-01-05T05:06:07'); // Sunday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            new TimeInterval(new DateTime('2025-01-01T02:03:04'), new DateTime('2025-01-04')),
        ], $intervals);
    }

    function testGetIntervalsEmpty()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTime('2025-01-01T02:03:04'); // Wednesday
        $end_instant = new DateTime('2025-01-01T02:03:04'); // Wednesday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }

    function testGetIntervalsEmptyAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTime('2025-01-04T02:03:04'); // Saturday
        $end_instant = new DateTime('2025-01-05T05:06:07'); // Sunday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }
}