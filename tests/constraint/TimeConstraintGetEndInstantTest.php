<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\OrTimeConstraint;
use Yolisses\TimeConstraints\Constraint\SingleIntervalTimeConstraint;
use Yolisses\TimeConstraints\Constraint\TimeOfDayTimeConstraint;

class TimeConstraintGetEndInstantTest extends TestCase
{
    public function testGetEndInstant()
    {
        //   0 1 2 3 4 5 6 7 8 9
        // 01████
        // 02  ████      ██
        // 03        ██████
        // 04        ████
        $time_constraint = new OrTimeConstraint([
            SingleIntervalTimeConstraint::fromStrings('2025-01-01 00:00', '2025-01-01 02:00'), // 2h
            SingleIntervalTimeConstraint::fromStrings('2025-01-02 01:00', '2025-01-02 03:00'), // 2h
            SingleIntervalTimeConstraint::fromStrings('2025-01-02 06:00', '2025-01-02 07:00'), // 1h 
            SingleIntervalTimeConstraint::fromStrings('2025-01-03 04:00', '2025-01-03 07:00'), // 3h
            SingleIntervalTimeConstraint::fromStrings('2025-01-04 04:00', '2025-01-04 06:00'), // 2h
        ]);

        $start_instant = new DateTimeImmutable('2025-01-01 00:00');
        $duration = 7 * 3600;  // 7h

        $end_instant = $time_constraint->getEndInstant($start_instant, $duration);

        $this->assertEquals(new DateTimeImmutable('2025-01-03 06:00'), $end_instant);
    }

    public function testGetEndInstantWithNegativeDuration()
    {
        //   0 1 2 3 4 5 6 7 8 9
        // 01████
        // 02  ████      ██
        // 03        ██████
        // 04        ████
        $time_constraint = new OrTimeConstraint([
            SingleIntervalTimeConstraint::fromStrings('2025-01-01 00:00', '2025-01-01 02:00'), // 2h
            SingleIntervalTimeConstraint::fromStrings('2025-01-02 01:00', '2025-01-02 03:00'), // 2h
            SingleIntervalTimeConstraint::fromStrings('2025-01-02 06:00', '2025-01-02 07:00'), // 1h
            SingleIntervalTimeConstraint::fromStrings('2025-01-03 04:00', '2025-01-03 07:00'), // 3h
            SingleIntervalTimeConstraint::fromStrings('2025-01-04 04:00', '2025-01-04 06:00'), // 2h
        ]);

        $start_instant = new DateTimeImmutable('2025-01-03 06:00');
        $duration = -7 * 3600;  // -7h

        $end_instant = $time_constraint->getEndInstant($start_instant, $duration);

        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:00'), $end_instant);
    }

    public function testDependingOnMaxInstant()
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
        $start_instant = new DateTimeImmutable('2025-01-01 00:00');
        $time_constraint = new TimeOfDayTimeConstraint('03:00', '05:00');

        $this->assertEquals(new DateTimeImmutable('2025-01-08 04:00'), $time_constraint->getEndInstant($start_instant, $duration));
        $this->assertEquals(new DateTimeImmutable('2025-01-08 04:00'), $time_constraint->getEndInstant($start_instant, $duration, 1000, 1000));

        $this->expectException(Exception::class);
        $time_constraint->getEndInstant($start_instant, $duration, 10, 1000);
    }
}