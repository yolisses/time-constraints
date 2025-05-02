<?php

use League\Period\Bounds;
use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\BeforeTimeConstraint;

class BeforeTimeConstraintTest extends TestCase
{
    public function testClampIntervalAfter()
    {
        $constraint = new BeforeTimeConstraint(new DateTimeImmutable('2025-01-01'), true);
        $clampPeriod = Period::fromDate('2025-01-02', '2025-01-03');
        $this->assertEquals(new Sequence(), $constraint->getSequence($clampPeriod));
    }

    public function testClampIntervalMetByWithStartIncluded()
    {
        $constraint = new BeforeTimeConstraint(new DateTimeImmutable('2025-01-01'), true);
        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02', Bounds::IncludeAll);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-01', '2025-01-01', Bounds::IncludeAll)
        ), $constraint->getSequence($clampPeriod));
    }

    public function testClampIntervalMetByWithStartExcluded()
    {
        $constraint = new BeforeTimeConstraint(new DateTimeImmutable('2025-01-01'), false);
        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02', Bounds::IncludeAll);
        $this->assertEquals(new Sequence(), $constraint->getSequence($clampPeriod));
    }

    public function testClampIntervalBefore()
    {
        $constraint = new BeforeTimeConstraint(new DateTimeImmutable('2025-01-03'), false);
        $clampPeriod = Period::fromDate('2025-01-01', '2025-01-02', Bounds::IncludeAll);
        $this->assertEquals(new Sequence(
            Period::fromDate('2025-01-01', '2025-01-02', Bounds::IncludeAll)
        ), $constraint->getSequence($clampPeriod));
    }
}