<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class TimeConstraintGetEndInstantTest extends TestCase
{
    public function testGetEndInstant()
    {
        //   0 1 2 3 4 5 6 7 8 9
        // 01████
        // 02  ████      ██
        // 03        ██████
        // 04        ████
        $time_constraint = new class () extends TimeConstraint {
            public function getIntervals(DateTime $start_instant, DateTime $end_instant): array
            {
                return [
                    new TimeInterval(new DateTime('2025-01-01 00:00'), new DateTime('2025-01-01 02:00')), // 2h
                    new TimeInterval(new DateTime('2025-01-02 01:00'), new DateTime('2025-01-02 03:00')), // 2h
                    new TimeInterval(new DateTime('2025-01-02 06:00'), new DateTime('2025-01-02 07:00')), // 1h 
                    new TimeInterval(new DateTime('2025-01-03 04:00'), new DateTime('2025-01-03 07:00')), // 3h
                    new TimeInterval(new DateTime('2025-01-04 04:00'), new DateTime('2025-01-04 06:00')), // 2h
                ];
            }
        };

        $start_instant = new DateTime('2025-01-01 00:00:00');
        $duration = 7 * 3600;  // 7h

        $end_instant = $time_constraint->getEndInstant($start_instant, $duration);

        $this->assertEquals(new DateTime('2025-01-03 06:00'), $end_instant);
    }
}