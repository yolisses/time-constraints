<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\DaysOfWeekTimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriod;

class DaysOfWeekTimeConstraintTest extends TestCase
{
    function testGetPeriods()
    {
        $days_of_week = [
            1, // Monday
            3, // Wednesday
            6, // Saturday
        ];
        $constraint = new DaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTimeImmutable('2025-01-01 02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-09 05:06:07'); // Thursday

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([
            TimePeriod::fromStrings('2025-01-01 02:03:04', '2025-01-02'),
            TimePeriod::fromStrings('2025-01-04', '2025-01-05'),
            TimePeriod::fromStrings('2025-01-06', '2025-01-07'),
            TimePeriod::fromStrings('2025-01-08', '2025-01-09'),
        ], $periods);
    }


    public function testGetPeriods2()
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

        $periods = $constraint->getSequence($start_instant, $end_instant);

        $this->assertEquals([
            TimePeriod::fromStrings('2025-01-01 08:00:00', '2025-01-02 00:00:00'), // Wednesday
            TimePeriod::fromStrings('2025-01-03 00:00:00', '2025-01-04 00:00:00'), // Friday
            TimePeriod::fromStrings('2025-01-06 00:00:00', '2025-01-09 00:00:00'), // Monday, Tuesday, Wednesday
            TimePeriod::fromStrings('2025-01-10 00:00:00', '2025-01-10 17:00:00'), // Friday
        ], $periods);
    }

    function testGetPeriodsEmptyDaysOfWeek()
    {
        $days_of_week = [];
        $constraint = new DaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTimeImmutable('2025-01-01 02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-09 05:06:07'); // Thursday

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([], $periods);
    }

    function testGetPeriodsEmptyResult()
    {
        $days_of_week = [
            1, // Monday
            2, // Tuesday
        ];
        $constraint = new DaysOfWeekTimeConstraint($days_of_week);

        $start_instant = new DateTimeImmutable('2025-01-01 02:03:04'); // Wednesday
        $end_instant = new DateTimeImmutable('2025-01-05 05:06:07'); // Sunday

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([], $periods);
    }
}