<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class TimeConstraintDurationTest extends TestCase
{
    public function testGetTotalDuration()
    {
        $time_constraint = new class () extends TimeConstraint {
            public function getIntervals(DateTimeImmutable $start_instant, DateTimeImmutable $end_instant): array
            {
                return [
                    TimeInterval::fromStrings('2025-01-01 01:00:00', '2025-01-01 04:00:06'), // 3 hours 6 seconds
                    TimeInterval::fromStrings('2025-01-02 07:00:00', '2025-01-02 09:04:00'), // 2 hours 4 minutes
                    TimeInterval::fromStrings('2025-01-03 15:00:00', '2025-01-03 16:00:02'), // 1 hours 2 seconds
                ];
            }
        };

        // Since getIntervals is mocked, these values are irrelevant
        $start_instant = new DateTimeImmutable();
        $end_instant = new DateTimeImmutable();

        $total_duration = $time_constraint->getTotalDuration($start_instant, $end_instant);

        $expected_duration = 3 * 3600 + 6 + 2 * 3600 + 4 * 60 + 1 * 3600 + 2;

        $this->assertEquals($expected_duration, $total_duration);
    }
}