<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\TimeOfDayTimeConstraint;

class TimeOfDayTimeConstraintTest extends TestCase
{
    function testGetPeriods()
    {
        $timeStart = '10:00:00';
        $timeEnd = '12:00:00';
        $constraint = new TimeOfDayTimeConstraint($timeStart, $timeEnd);

        $clampPeriod = Period::fromDate('2025-01-01 11:03:04', '2025-01-09 11:06:07');

        $periods = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-01 11:03:04', '2025-01-01 12:00'),
            Period::fromDate('2025-01-02 10:00', '2025-01-02 12:00'),
            Period::fromDate('2025-01-03 10:00', '2025-01-03 12:00'),
            Period::fromDate('2025-01-04 10:00', '2025-01-04 12:00'),
            Period::fromDate('2025-01-05 10:00', '2025-01-05 12:00'),
            Period::fromDate('2025-01-06 10:00', '2025-01-06 12:00'),
            Period::fromDate('2025-01-07 10:00', '2025-01-07 12:00'),
            Period::fromDate('2025-01-08 10:00', '2025-01-08 12:00'),
            Period::fromDate('2025-01-09 10:00', '2025-01-09 11:06:07'),
        ), $periods);
    }
}