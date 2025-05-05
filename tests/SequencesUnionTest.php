<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\SequencesUnion;

class SequencesUnionTest extends TestCase
{
    /**
     * Test intersection with an empty array of sequences.
     */
    public function testEmptySequences(): void
    {
        $result = SequencesUnion::union([]);
        $this->assertEquals(new Sequence(), $result);
    }

    /**
     * Test intersection with a single sequence.
     */
    public function testSingleSequence(): void
    {
        $sequence = new Sequence(
            Period::fromDate('2025-01-01', '2025-01-10'),
            Period::fromDate('2025-02-01', '2025-02-15')
        );
        $result = SequencesUnion::union([$sequence]);

        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-01', '2025-01-10'),
            Period::fromDate('2025-02-01', '2025-02-15'),
        ), $result);
    }

    /**
     * Test intersection with two sequences that have overlapping periods.
     */
    public function testTwoSequencesWithOverlap(): void
    {
        $sequence1 = new Sequence(
            Period::fromDate('2025-01-01', '2025-01-10'),
            Period::fromDate('2025-02-01', '2025-02-15')
        );
        $sequence2 = new Sequence(
            Period::fromDate('2025-01-05', '2025-01-15'),
            Period::fromDate('2025-02-10', '2025-02-20')
        );

        $result = SequencesUnion::union([$sequence1, $sequence2]);

        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-05', '2025-01-10'),
            Period::fromDate('2025-02-10', '2025-02-15'),
        ), $result);
    }

    /**
     * Test intersection with three sequences that have overlapping periods.
     */
    public function testThreeSequencesWithOverlap(): void
    {
        $sequence1 = new Sequence(
            Period::fromDate('2025-01-01', '2025-01-10'),
            Period::fromDate('2025-02-01', '2025-02-15')
        );
        $sequence2 = new Sequence(
            Period::fromDate('2025-01-05', '2025-01-15'),
            Period::fromDate('2025-02-10', '2025-02-20')
        );
        $sequence3 = new Sequence(
            Period::fromDate('2025-01-07', '2025-01-20'),
            Period::fromDate('2025-02-12', '2025-02-25')
        );

        $result = SequencesUnion::union([$sequence1, $sequence2, $sequence3]);

        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-07', '2025-01-10'),
            Period::fromDate('2025-02-12', '2025-02-15'),
        ), $result);
    }

    /**
     * Test intersection with sequences that have no overlapping periods.
     */
    public function testNoOverlappingPeriods(): void
    {
        $sequence1 = new Sequence(
            Period::fromDate('2025-01-01', '2025-01-05')
        );
        $sequence2 = new Sequence(
            Period::fromDate('2025-01-06', '2025-01-10')
        );

        $result = SequencesUnion::union([$sequence1, $sequence2]);

        $this->assertEquals(new Sequence(), $result);
    }

    /**
     * Test intersection with an empty sequence in the input.
     */
    public function testEmptySequenceInInput(): void
    {
        $sequence1 = new Sequence(
            Period::fromDate('2025-01-01', '2025-01-10')
        );
        $sequence2 = new Sequence(); // Empty sequence

        $result = SequencesUnion::union([$sequence1, $sequence2]);

        $this->assertEquals(new Sequence(), $result);
    }

    /**
     * Test intersection with identical sequences.
     */
    public function testIdenticalSequences(): void
    {
        $sequence = new Sequence(
            Period::fromDate('2025-01-01', '2025-01-10')
        );
        $sequences = [$sequence, $sequence, $sequence];

        $result = SequencesUnion::union($sequences);

        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-01', '2025-01-10'),
        ), $result);
    }
}