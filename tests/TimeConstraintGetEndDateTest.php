<?php

use League\Period\Period;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\OrTimeConstraint;
use Yolisses\TimeConstraints\SinglePeriodTimeConstraint;
use Yolisses\TimeConstraints\TimeOfDayTimeConstraint;

class TimeConstraintGetEndDateTest extends TestCase
{
    public function testGetEndDate()
    {
        //   0 1 2 3 4 5 6 7 8 9
        // 01████
        // 02  ████      ██
        // 03        ██████
        // 04        ████
        $timeConstraint = new OrTimeConstraint([
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-01 00:00', '2025-01-01 02:00')), // 2h
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-02 01:00', '2025-01-02 03:00')), // 2h
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-02 06:00', '2025-01-02 07:00')), // 1h 
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-03 04:00', '2025-01-03 07:00')), // 3h
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-04 04:00', '2025-01-04 06:00')), // 2h
        ]);

        $start = new DateTimeImmutable('2025-01-01 00:00');
        $duration = 7 * 3600;  // 7h

        $end = $timeConstraint->getEndDate($start, $duration);

        $this->assertEquals(new DateTimeImmutable('2025-01-03 06:00'), $end);
    }

    public function testGetEndDateWithNegativeDuration()
    {
        //   0 1 2 3 4 5 6 7 8 9
        // 01████
        // 02  ████      ██
        // 03        ██████
        // 04        ████
        $timeConstraint = new OrTimeConstraint([
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-01 00:00', '2025-01-01 02:00')), // 2h
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-02 01:00', '2025-01-02 03:00')), // 2h
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-02 06:00', '2025-01-02 07:00')), // 1h
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-03 04:00', '2025-01-03 07:00')), // 3h
            new SinglePeriodTimeConstraint(Period::fromDate('2025-01-04 04:00', '2025-01-04 06:00')), // 2h
        ]);

        $start = new DateTimeImmutable('2025-01-03 06:00');
        $duration = -7 * 3600;  // -7h

        $end = $timeConstraint->getEndDate($start, $duration);

        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:00'), $end);
    }

    public function testDependingOnMaxDate()
    {
        //                      10  12  14  16  18  20  22  24
        //   0 1 2 3 4 5 6 7 8 9  11  13  15  17  19  21  23
        // 01      ████
        // 02      ████
        // 03      ████
        // 04      ████
        // 05      ████
        // 06      ████
        // 07      ████
        // 08      ████
        // 09      ████

        $duration = 15 * 3600; // 15h
        $start = new DateTimeImmutable('2025-01-01 00:00');
        $timeConstraint = new TimeOfDayTimeConstraint('03:00', '05:00');

        $this->assertEquals(new DateTimeImmutable('2025-01-08 04:00'), $timeConstraint->getEndDate($start, $duration));
        $this->assertEquals(new DateTimeImmutable('2025-01-08 04:00'), $timeConstraint->getEndDate($start, $duration, 1000, 1000));

        $this->expectException(Exception::class);
        $timeConstraint->getEndDate($start, $duration, 10, 1000);
    }
}