<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\WeekdaysTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class WeekdaysTimeConstraintTest extends TestCase
{

    function testIsWeekend()
    {
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-01'))); // Wednesday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-02'))); // Thursday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-03'))); // Friday
        $this->assertTrue(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-04'))); // Saturday
        $this->assertTrue(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-05'))); // Sunday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-06'))); // Monday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-07'))); // Tuesday
    }

    function testGetIntervals()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-01T02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-30T05:06:07'); // Thursday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-01T02:03:04', '2025-01-04', true, false),
            TimeInterval::fromStrings('2025-01-06', '2025-01-11', true, false),
            TimeInterval::fromStrings('2025-01-13', '2025-01-18', true, false),
            TimeInterval::fromStrings('2025-01-20', '2025-01-25', true, false),
            TimeInterval::fromStrings('2025-01-27', '2025-01-30T05:06:07', true, false),
        ], $intervals);
    }

    function testGetIntervalsStartingAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-04T02:03:04'); // Saturday
        $end_instant = new DateTimeImmutable('2025-01-30T05:06:07'); // Thursday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-06', '2025-01-11', true, false),
            TimeInterval::fromStrings('2025-01-13', '2025-01-18', true, false),
            TimeInterval::fromStrings('2025-01-20', '2025-01-25', true, false),
            TimeInterval::fromStrings('2025-01-27', '2025-01-30T05:06:07', true, false),
        ], $intervals);
    }

    function testGetIntervalsEndingAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-01T02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-05T05:06:07'); // Sunday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-01T02:03:04', '2025-01-04', true, false),
        ], $intervals);
    }

    function testGetIntervalsEmpty()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-01T02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-01T02:03:04'); // Wednesday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }

    function testGetIntervalsEmptyAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-04T02:03:04'); // Saturday
        $end_instant = new DateTimeImmutable('2025-01-05T05:06:07'); // Sunday
        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }
}