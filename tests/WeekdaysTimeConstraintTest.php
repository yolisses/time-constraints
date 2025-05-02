<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\WeekdaysTimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriod;

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

    function testGetPeriods()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-01T02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-30T05:06:07'); // Thursday
        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([
            TimePeriod::fromStrings('2025-01-01T02:03:04', '2025-01-04'),
            TimePeriod::fromStrings('2025-01-06', '2025-01-11'),
            TimePeriod::fromStrings('2025-01-13', '2025-01-18'),
            TimePeriod::fromStrings('2025-01-20', '2025-01-25'),
            TimePeriod::fromStrings('2025-01-27', '2025-01-30T05:06:07'),
        ], $periods);
    }

    function testGetPeriodsStartingAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-04T02:03:04'); // Saturday
        $end_instant = new DateTimeImmutable('2025-01-30T05:06:07'); // Thursday
        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([
            TimePeriod::fromStrings('2025-01-06', '2025-01-11'),
            TimePeriod::fromStrings('2025-01-13', '2025-01-18'),
            TimePeriod::fromStrings('2025-01-20', '2025-01-25'),
            TimePeriod::fromStrings('2025-01-27', '2025-01-30T05:06:07'),
        ], $periods);
    }

    function testGetPeriodsEndingAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-01T02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-05T05:06:07'); // Sunday
        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([
            TimePeriod::fromStrings('2025-01-01T02:03:04', '2025-01-04'),
        ], $periods);
    }

    function testGetPeriodsEmpty()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-01T02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-01T02:03:04'); // Wednesday
        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([], $periods);
    }

    function testGetPeriodsEmptyAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        $start_instant = new DateTimeImmutable('2025-01-04T02:03:04'); // Saturday
        $end_instant = new DateTimeImmutable('2025-01-05T05:06:07'); // Sunday
        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([], $periods);
    }
}