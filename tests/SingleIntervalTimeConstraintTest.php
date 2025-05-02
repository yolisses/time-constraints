<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\SinglePeriodTimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriod;

class SinglePeriodTimeConstraintTest extends TestCase
{
    public function testGetPeriods()
    {
        $time_period = new TimePeriod(
            new DateTimeImmutable('2025-01-02 05:00:00'),
            new DateTimeImmutable('2025-01-02 10:00:00')
        );
        $constraint = new SinglePeriodTimeConstraint($time_period);

        $start_instant = new DateTimeImmutable('2025-01-01 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-03 09:06:07');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([
            TimePeriod::fromStrings('2025-01-02 05:00:00', '2025-01-02 10:00:00'),
        ], $periods);
    }

    public function testGetPeriods2()
    {
        $time_period = new TimePeriod(
            new DateTimeImmutable('2025-01-02 05:00:00'),
            new DateTimeImmutable('2025-01-02 10:00:00')
        );
        $constraint = new SinglePeriodTimeConstraint($time_period);

        $start_instant = new DateTimeImmutable('2025-01-02 06:03:04');
        $end_instant = new DateTimeImmutable('2025-01-02 09:06:07');

        $periods = $constraint->getSequence($start_instant, $end_instant);
        $this->assertEquals([
            TimePeriod::fromStrings('2025-01-02 06:03:04', '2025-01-02 09:06:07'),
        ], $periods);
    }
}