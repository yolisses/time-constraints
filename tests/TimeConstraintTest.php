<?php

use League\Period\Bounds;
use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\TimeConstraint;

class TimeConstraintTest extends TestCase
{
    private \DateTimeImmutable $baseDate;

    protected function setUp(): void
    {
        $this->baseDate = new \DateTimeImmutable('2023-01-01 00:00:00');
    }

    private function createDate(string $offset): \DateTimeImmutable
    {
        return $this->baseDate->modify($offset);
    }

    private function createPeriod(string $startOffset, string $endOffset, Bounds $bounds): Period
    {
        return Period::fromDate(
            $this->createDate($startOffset),
            $this->createDate($endOffset),
            $bounds,
        );
    }

    public function testEmptySequenceReturnsEmpty(): void
    {
        $sequence = new Sequence();
        $start = $this->createDate('+1 day');
        $end = $this->createDate('+2 days');

        $result = TimeConstraint::clampSequence($sequence, $start, $end);

        $this->assertTrue($result->isEmpty());
    }

    public function testNoOverlapExcludesPeriods(): void
    {
        $sequence = new Sequence(
            $this->createPeriod('-2 days', '-1 day', Bounds::IncludeAll), // Before clamp range
            $this->createPeriod('+3 days', '+4 days', Bounds::IncludeAll)  // After clamp range
        );
        $start = $this->createDate('+1 day');
        $end = $this->createDate('+2 days');

        $result = TimeConstraint::clampSequence($sequence, $start, $end);

        $this->assertTrue($result->isEmpty());
    }

    public function testFullOverlapIncludesUnchanged(): void
    {
        $period = $this->createPeriod('+1 day 12:00', '+1 day 18:00');
        $sequence = new Sequence($period);
        $start = $this->createDate('+1 day');
        $end = $this->createDate('+2 days');

        $result = TimeConstraint::clampSequence($sequence, $start, $end);

        $this->assertEquals(
            new Sequence($this->createPeriod('+1 day 12:00', '+1 day 18:00')),
            $result
        );
    }

    public function testPartialOverlapTrimsPeriod(): void
    {
        $period = $this->createPeriod('+1 day', '+3 days');
        $sequence = new Sequence($period);
        $start = $this->createDate('+2 days');
        $end = $this->createDate('+4 days');

        $result = TimeConstraint::clampSequence($sequence, $start, $end);

        $this->assertEquals(
            new Sequence($this->createPeriod('+2 days', '+3 days')),
            $result
        );
    }

    public function testBoundaryOverlapIncludesPeriod(): void
    {
        $period = $this->createPeriod('+1 day', '+2 days');
        $sequence = new Sequence($period);
        $start = $this->createDate('+1 day');
        $end = $this->createDate('+2 days');

        $result = TimeConstraint::clampSequence($sequence, $start, $end);

        $this->assertEquals(
            new Sequence($this->createPeriod('+2 days', '+3 days')),
            $result
        );
    }

    public function testMultiplePeriodsFiltersCorrectly(): void
    {
        $sequence = new Sequence(
            $this->createPeriod('-2 days', '-1 day'), // No overlap
            $this->createPeriod('+1 day', '+3 days'), // Partial overlap
            $this->createPeriod('+1 day 12:00', '+1 day 18:00'), // Full overlap
            $this->createPeriod('+4 days', '+5 days') // No overlap
        );
        $start = $this->createDate('+1 day');
        $end = $this->createDate('+2 days');

        $result = TimeConstraint::clampSequence($sequence, $start, $end);

        $resultPeriods = $result->toList();
        $this->assertCount(2, $resultPeriods);
        $this->assertEquals($this->createDate('+1 day'), $resultPeriods[0]->startDate);
        $this->assertEquals($this->createDate('+2 days'), $resultPeriods[0]->end());
        $this->assertEquals($this->createDate('+1 day 12:00'), $resultPeriods[1]->startDate);
        $this->assertEquals($this->createDate('+1 day 18:00'), $resultPeriods[1]->end());
    }

    public function testInvalidClampRangeThrowsException(): void
    {
        $sequence = new Sequence($this->createPeriod('+1 day', '+2 days'));
        $start = $this->createDate('+2 days');
        $end = $this->createDate('+1 day');

        $this->expectException(\InvalidArgumentException::class);
        TimeConstraint::clampSequence($sequence, $start, $end);
    }
}