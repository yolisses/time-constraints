<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;

class AndTimeConstraintTest extends TestCase
{
    public function testGetSequenceWithEmptyConstraints()
    {
        $constraint = new AndTimeConstraint([]);
        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02');
        $this->assertEquals(new Sequence(), $constraint->getSequence($clampPeriod));
    }

    public function testGetSequenceWithSingleConstraint()
    {
        $mockConstraint = $this->createMock(TimeConstraint::class);
        $mockConstraint->method('getSequence')->willReturn(
            new Sequence(Period::fromDate('2025-01-01', '2025-01-02'))
        );

        $constraint = new AndTimeConstraint([$mockConstraint]);
        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02');

        $this->assertEquals(
            new Sequence(Period::fromDate('2025-01-01', '2025-01-02')),
            $constraint->getSequence($clampPeriod)
        );
    }

    public function testGetSequenceWithMultipleConstraints()
    {
        $mockConstraint1 = $this->createMock(TimeConstraint::class);
        $mockConstraint1->method('getSequence')->willReturn(
            new Sequence(Period::fromDate('2025-01-01', '2025-01-04'))
        );

        $mockConstraint2 = $this->createMock(TimeConstraint::class);
        $mockConstraint2->method('getSequence')->willReturn(
            new Sequence(Period::fromDate('2025-01-02', '2025-01-05'))
        );

        $mockConstraint3 = $this->createMock(TimeConstraint::class);
        $mockConstraint3->method('getSequence')->willReturn(
            new Sequence(Period::fromDate('2025-01-03', '2025-01-06'))
        );

        $constraint = new AndTimeConstraint([$mockConstraint1, $mockConstraint2, $mockConstraint3]);
        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-06');

        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-03', '2025-01-04'),
        ), $constraint->getSequence($clampPeriod));
    }
}