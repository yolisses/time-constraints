<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\WeekdaysTimeConstraint;

class WeekdaysTimeConstraintTest extends TestCase
{

    public function testIsWeekend()
    {
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-01'))); // Wednesday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-02'))); // Thursday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-03'))); // Friday
        $this->assertTrue(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-04'))); // Saturday
        $this->assertTrue(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-05'))); // Sunday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-06'))); // Monday
        $this->assertFalse(WeekdaysTimeConstraint::getIsWeekend(new DateTimeImmutable('2025-01-07'))); // Tuesday
    }

    public function testGetPeriods()
    {
        $constraint = new WeekdaysTimeConstraint();
        // Wednesday - Friday
        $clampPeriod = Period::fromDate('2025-01-01T02:03:04', '2025-01-30T05:06:07');
        $periods = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-01T02:03:04', '2025-01-04'),
            Period::fromDate('2025-01-06', '2025-01-11'),
            Period::fromDate('2025-01-13', '2025-01-18'),
            Period::fromDate('2025-01-20', '2025-01-25'),
            Period::fromDate('2025-01-27', '2025-01-30T05:06:07'),
        ), $periods);
    }

    public function testGetPeriodsStartingAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        // Saturday - Thursday
        $clampPeriod = Period::fromDate('2025-01-04T02:03:04', '2025-01-30T05:06:07');
        $periods = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-06', '2025-01-11'),
            Period::fromDate('2025-01-13', '2025-01-18'),
            Period::fromDate('2025-01-20', '2025-01-25'),
            Period::fromDate('2025-01-27', '2025-01-30T05:06:07'),
        ), $periods);
    }

    public function testGetPeriodsEndingAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        // Wednesday - Sunday
        $clampPeriod = Period::fromDate('2025-01-01T02:03:04', '2025-01-05T05:06:07');
        $periods = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-01T02:03:04', '2025-01-04'),
        ), $periods);
    }

    public function testGetPeriodsEmpty()
    {
        $constraint = new WeekdaysTimeConstraint();
        // Wednesday - Wednesday
        $clampPeriod = Period::fromDate('2025-01-01T02:03:04', '2025-01-01T02:03:04');
        $periods = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(), $periods);
    }

    public function testGetPeriodsEmptyAtWeekend()
    {
        $constraint = new WeekdaysTimeConstraint();
        // Saturday - Sunday
        $clampPeriod = Period::fromDate('2025-01-04T02:03:04', '2025-01-05T05:06:07');
        $periods = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(), $periods);
    }
}