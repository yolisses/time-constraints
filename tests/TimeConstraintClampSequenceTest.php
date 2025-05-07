<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\TimeConstraint;

class TimeConstraintClampSequenceTest extends TestCase
{
    public function testClampSequenceWithIntersectingPeriods()
    {
        $sequence = new Sequence(
            Period::fromDate('2025-01-01 00:00:00', '2025-01-01 12:00:00'),
            Period::fromDate('2025-01-02 00:00:00', '2025-01-02 12:00:00')
        );

        $clampPeriod = Period::fromDate('2025-01-01 06:00:00', '2025-01-02 06:00:00');

        $result = TimeConstraint::clampSequence($sequence, $clampPeriod);

        $expected = new Sequence(
            Period::fromDate('2025-01-01 06:00:00', '2025-01-01 12:00:00'),
            Period::fromDate('2025-01-02 00:00:00', '2025-01-02 06:00:00')
        );

        $this->assertEquals($expected, $result);
    }

    public function testClampSequenceWithNoIntersectingPeriods()
    {
        $sequence = new Sequence(
            Period::fromDate('2025-01-01 00:00:00', '2025-01-01 12:00:00'),
            Period::fromDate('2025-01-02 00:00:00', '2025-01-02 12:00:00')
        );

        $clampPeriod = Period::fromDate('2025-01-03 00:00:00', '2025-01-03 12:00:00');

        $result = TimeConstraint::clampSequence($sequence, $clampPeriod);

        $expected = new Sequence();

        $this->assertEquals($expected, $result);
    }

    public function testClampSequenceWithExactMatch()
    {
        $sequence = new Sequence(
            Period::fromDate('2025-01-01 00:00:00', '2025-01-01 12:00:00')
        );

        $clampPeriod = Period::fromDate('2025-01-01 00:00:00', '2025-01-01 12:00:00');

        $result = TimeConstraint::clampSequence($sequence, $clampPeriod);

        $expected = new Sequence(
            Period::fromDate('2025-01-01 00:00:00', '2025-01-01 12:00:00')
        );

        $this->assertEquals($expected, $result);
    }

    public function testClampSequenceWithEmptySequence()
    {
        $sequence = new Sequence();

        $clampPeriod = Period::fromDate('2025-01-01 00:00:00', '2025-01-01 12:00:00');

        $result = TimeConstraint::clampSequence($sequence, $clampPeriod);

        $expected = new Sequence();

        $this->assertEquals($expected, $result);
    }
}