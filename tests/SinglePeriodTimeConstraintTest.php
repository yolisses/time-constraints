<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\SinglePeriodTimeConstraint;

class SinglePeriodTimeConstraintTest extends TestCase
{
    public function testGetPeriods()
    {
        $period = Period::fromDate('2025-01-02 05:00:00', '2025-01-02 10:00:00');
        $constraint = new SinglePeriodTimeConstraint($period);

        $clampPeriod = Period::fromDate('2025-01-01 06:03:04', '2025-01-03 09:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-02 05:00:00', '2025-01-02 10:00:00'),
        ), $sequence);
    }

    public function testGetPeriods2()
    {
        $period = Period::fromDate('2025-01-02 05:00:00', '2025-01-02 10:00:00');
        $constraint = new SinglePeriodTimeConstraint($period);

        $clampPeriod = Period::fromDate('2025-01-02 06:03:04', '2025-01-02 09:06:07');

        $periods = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-02 06:03:04', '2025-01-02 09:06:07'),
        ), $periods);
    }
}