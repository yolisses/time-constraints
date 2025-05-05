<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\OrTimeConstraint;
use Yolisses\TimeConstraints\TimeConstraint;

class OrTimeConstraintTest extends TestCase
{
    public function testGetSequence()
    {
        $time_constraint1 = $this->createMock(TimeConstraint::class);
        $time_constraint2 = $this->createMock(TimeConstraint::class);
        $time_constraint3 = $this->createMock(TimeConstraint::class);

        $time_constraint1->method('getSequence')->willReturn(new Sequence(
            Period::fromDate('2021-01-01', '2021-01-03')
        ));

        $time_constraint2->method('getSequence')->willReturn(new Sequence(
            Period::fromDate('2021-01-02', '2021-01-04')
        ));

        $time_constraint3->method('getSequence')->willReturn(new Sequence(
            Period::fromDate('2021-01-05', '2021-01-06')
        ));

        $or_time_constraint = new OrTimeConstraint([$time_constraint1, $time_constraint2, $time_constraint3]);

        $periods = $or_time_constraint->getSequence(Period::fromDate('2021-01-01', '2021-01-06'));

        $this->assertEquals(new Sequence(
            Period::fromDate('2021-01-01', '2021-01-04'),
            Period::fromDate('2021-01-05', '2021-01-06')
        ), $periods);
    }
}