<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\SingleDayTimeConstraint;

class SingleDayTimeConstraintTest extends TestCase
{
    public function testGetPeriods()
    {
        $day = new DateTimeImmutable('2025-01-02 05:00:00');
        $constraint = new SingleDayTimeConstraint($day);

        $clampPeriod = Period::fromDate('2025-01-01 02:03:04', '2025-01-03 05:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(Period::fromDate('2025-01-02 00:00:00', '2025-01-03 00:00:00')), $sequence);
    }

    public function testGetPeriods2()
    {
        $day = new DateTimeImmutable('2025-01-02 05:00:00');
        $constraint = new SingleDayTimeConstraint($day);

        $clampPeriod = Period::fromDate('2025-01-02 08:03:04', '2025-01-03 05:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(Period::fromDate('2025-01-02 08:03:04', '2025-01-03 00:00:00')), $sequence);
    }
}