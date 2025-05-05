<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\SequenceTimeConstraint;

class SequenceTimeConstraintTest extends TestCase
{
    public function testGetPeriodsEmpty()
    {
        $constraint = new SequenceTimeConstraint(new Sequence());

        $clampPeriod = Period::fromDate('2025-01-01 06:03:04', '2025-01-03 09:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(), $sequence);
    }

    public function testGetPeriodsWithoutClamp()
    {
        $constraint = new SequenceTimeConstraint(new Sequence(
            Period::fromDate('2025-01-02 05:00:00', '2025-01-02 7:00:00'),
            Period::fromDate('2025-01-02 08:00:00', '2025-01-02 10:00:00'),
        ));

        $clampPeriod = Period::fromDate('2025-01-01 06:03:04', '2025-01-03 09:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-02 05:00:00', '2025-01-02 7:00:00'),
            Period::fromDate('2025-01-02 08:00:00', '2025-01-02 10:00:00'),
        ), $sequence);
    }

    public function testGetPeriodsWithClamp()
    {
        $constraint = new SequenceTimeConstraint(new Sequence(
            Period::fromDate('2025-01-02 05:00:00', '2025-01-02 7:00:00'),
            Period::fromDate('2025-01-02 08:00:00', '2025-01-02 10:00:00'),
        ));

        $clampPeriod = Period::fromDate('2025-01-02 06:03:04', '2025-01-02 09:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-02 06:03:04', '2025-01-02 7:00:00'),
            Period::fromDate('2025-01-02 08:00:00', '2025-01-02 09:06:07'),
        ), $sequence);
    }
}