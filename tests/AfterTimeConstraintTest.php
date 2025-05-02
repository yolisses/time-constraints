<?php

use League\Period\Bounds;
use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\AfterTimeConstraint;

class AfterTimeConstraintTest extends TestCase
{
    public function testClampIntervalBefore()
    {
        $constraint = new AfterTimeConstraint(new DateTimeImmutable('2025-01-03'), true);
        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02');
        $this->assertEquals(new Sequence(), $constraint->getSequence($clampPeriod));
    }

    public function testClampIntervalMeetsWithStartIncluded()
    {
        $constraint = new AfterTimeConstraint(new DateTimeImmutable('2025-01-02'), true);
        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02', Bounds::IncludeAll);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-02', '2025-01-02', Bounds::IncludeAll)
        ), $constraint->getSequence($clampPeriod));
    }

    public function testClampIntervalMeetsWithStartExcluded()
    {
        $constraint = new AfterTimeConstraint(new DateTimeImmutable('2025-01-02'), false);
        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02', Bounds::IncludeAll);
        $this->assertEquals(new Sequence(), $constraint->getSequence($clampPeriod));
    }

    public function testClampIntervalAfter()
    {
        $constraint = new AfterTimeConstraint(new DateTimeImmutable('2025-01-01'), false);
        $clampPeriod = Period::fromDate('2025-01-02', '2025-01-03', Bounds::IncludeAll);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-02', '2025-01-03', Bounds::IncludeAll)
        ), $constraint->getSequence($clampPeriod));
    }
}