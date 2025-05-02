<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\MultiplePeriodsTimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriod;

class MultiplePeriodsTimeConstraintTest extends TestCase
{
    public function testGetPeriodsEmpty()
    {
        $constraint = new MultiplePeriodsTimeConstraint([]);

        $start_instant = new DateTimeImmutable('2025-01-01 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-03 09:06:07');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([], $periods);
    }

    public function testGetPeriodsWithoutClamp()
    {
        $constraint = new MultiplePeriodsTimeConstraint([
            TimePeriod::fromStrings('2025-01-02 05:00:00', '2025-01-02 7:00:00'),
            TimePeriod::fromStrings('2025-01-02 08:00:00', '2025-01-02 10:00:00'),
        ]);

        $start_instant = new DateTimeImmutable('2025-01-01 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-03 09:06:07');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([
            TimePeriod::fromStrings('2025-01-02 05:00:00', '2025-01-02 7:00:00'),
            TimePeriod::fromStrings('2025-01-02 08:00:00', '2025-01-02 10:00:00'),
        ], $periods);
    }

    public function testGetPeriodsWithClamp()
    {
        $constraint = new MultiplePeriodsTimeConstraint([
            TimePeriod::fromStrings('2025-01-02 05:00:00', '2025-01-02 7:00:00'),
            TimePeriod::fromStrings('2025-01-02 08:00:00', '2025-01-02 10:00:00'),
        ]);

        $start_instant = new DateTimeImmutable('2025-01-02 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-02 09:06:07');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([
            TimePeriod::fromStrings('2025-01-02 06:03:04', '2025-01-02 7:00:00'),
            TimePeriod::fromStrings('2025-01-02 08:00:00', '2025-01-02 09:06:07'),
        ], $periods);
    }
}