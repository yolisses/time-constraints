<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\MultipleIntervalsTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class MultipleIntervalsTimeConstraintTest extends TestCase
{
    public function testGetIntervalsEmpty()
    {
        $constraint = new MultipleIntervalsTimeConstraint([]);

        $start_instant = new DateTimeImmutable('2025-01-01 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-03 09:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([], $intervals);
    }

    public function testGetIntervalsWithoutClamp()
    {
        $constraint = new MultipleIntervalsTimeConstraint([
            TimeInterval::fromStrings('2025-01-02 05:00:00', '2025-01-02 7:00:00'),
            TimeInterval::fromStrings('2025-01-02 08:00:00', '2025-01-02 10:00:00'),
        ]);

        $start_instant = new DateTimeImmutable('2025-01-01 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-03 09:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-02 05:00:00', '2025-01-02 7:00:00'),
            TimeInterval::fromStrings('2025-01-02 08:00:00', '2025-01-02 10:00:00'),
        ], $intervals);
    }

    public function testGetIntervalsWithClamp()
    {
        $constraint = new MultipleIntervalsTimeConstraint([
            TimeInterval::fromStrings('2025-01-02 05:00:00', '2025-01-02 7:00:00'),
            TimeInterval::fromStrings('2025-01-02 08:00:00', '2025-01-02 10:00:00'),
        ]);

        $start_instant = new DateTimeImmutable('2025-01-02 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-02 09:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([
            TimeInterval::fromStrings('2025-01-02 06:03:04', '2025-01-02 7:00:00'),
            TimeInterval::fromStrings('2025-01-02 08:00:00', '2025-01-02 09:06:07'),
        ], $intervals);
    }
}