<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;

class SequenceTest extends TestCase
{
    public function testDirectComparison()
    {
        $sequence = new Sequence(
            Period::fromDate('2025-01-01', '2025-01-02'),
            Period::fromDate('2025-01-02', '2025-01-03'),
        );

        $this->assertNotEquals(new Sequence(
            Period::fromDate('2025-01-01', '2025-01-03'),
        ), $sequence);
    }

    public function testUnion()
    {
        $sequence = (new Sequence(
            Period::fromDate('2025-01-01', '2025-01-02'),
            Period::fromDate('2025-01-02', '2025-01-03'),
        ))->unions();

        $this->assertNotEquals((new Sequence(
            Period::fromDate('2025-01-01', '2025-01-03'),
        ))->unions(), $sequence);
    }
}