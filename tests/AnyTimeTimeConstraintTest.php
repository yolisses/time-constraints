<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\AnyTimeTimeConstraint;

class AnyTimeTimeConstraintTest extends TestCase
{
    public function testGetSequence()
    {
        $constraint = new AnyTimeTimeConstraint();

        $clampPeriod = Period::fromDate('2025-01-01 02:03:04', '2025-01-03 05:06:07');

        $sequence = $constraint->getSequence($clampPeriod);
        $this->assertEquals(new Sequence(Period::fromDate('2025-01-01 02:03:04', '2025-01-03 05:06:07')), $sequence);
    }
}