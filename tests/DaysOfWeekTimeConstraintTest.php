<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\DaysOfWeekTimeConstraint;

class DaysOfWeekTimeConstraintTest extends TestCase
{
    function testGetSequence()
    {
        $daysOfWeek = [
            1, // Monday
            3, // Wednesday
            6, // Saturday
        ];
        $constraint = new DaysOfWeekTimeConstraint($daysOfWeek);

        // Wednesday - Thursday
        $clampPeriod = Period::fromDate('2025-01-01 02:03:04', '2025-01-09 05:06:07');
        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-01 02:03:04', '2025-01-02'),
            Period::fromDate('2025-01-04', '2025-01-05'),
            Period::fromDate('2025-01-06', '2025-01-07'),
            Period::fromDate('2025-01-08', '2025-01-09'),
        ), $sequence);
    }

    public function testGetSequence2()
    {
        $daysOfWeek = [
            1, // Monday
            2, // Tuesday
            3, // Wednesday
            5, // Friday
        ];
        $constraint = new DaysOfWeekTimeConstraint($daysOfWeek);

        // Wednesday - Friday
        $clampPeriod = Period::fromDate('2025-01-01 08:00:00', '2025-01-10 17:00:00');

        $sequence = $constraint->getSequence($clampPeriod);

        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-01 08:00:00', '2025-01-02 00:00:00'), // Wednesday
            Period::fromDate('2025-01-03 00:00:00', '2025-01-04 00:00:00'), // Friday
            Period::fromDate('2025-01-06 00:00:00', '2025-01-07 00:00:00'), // Monday
            Period::fromDate('2025-01-07 00:00:00', '2025-01-08 00:00:00'), // Tuesday
            Period::fromDate('2025-01-08 00:00:00', '2025-01-09 00:00:00'), // Wednesday
            Period::fromDate('2025-01-10 00:00:00', '2025-01-10 17:00:00'), // Friday
        ), $sequence);
    }

    function testGetSequenceEmptyDaysOfWeek()
    {
        $daysOfWeek = [];
        $constraint = new DaysOfWeekTimeConstraint($daysOfWeek);

        // Wednesday - Thursday
        $clampPeriod = Period::fromDate('2025-01-01 02:03:04', '2025-01-09 05:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(), $sequence);
    }

    function testGetSequenceEmptyResult()
    {
        $daysOfWeek = [
            1, // Monday
            2, // Tuesday
        ];
        $constraint = new DaysOfWeekTimeConstraint($daysOfWeek);

        // Wednesday - Sunday
        $clampPeriod = Period::fromDate('2025-01-01 02:03:04', '2025-01-05 05:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(), $sequence);
    }
}