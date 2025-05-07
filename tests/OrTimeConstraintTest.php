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
        $timeConstraint1 = $this->createMock(TimeConstraint::class);
        $timeConstraint2 = $this->createMock(TimeConstraint::class);
        $timeConstraint3 = $this->createMock(TimeConstraint::class);

        $timeConstraint1->method('getSequence')->willReturn(new Sequence(
            Period::fromDate('2021-01-01', '2021-01-03')
        ));

        $timeConstraint2->method('getSequence')->willReturn(new Sequence(
            Period::fromDate('2021-01-02', '2021-01-04')
        ));

        $timeConstraint3->method('getSequence')->willReturn(new Sequence(
            Period::fromDate('2021-01-05', '2021-01-06')
        ));

        $orTimeConstraint = new OrTimeConstraint([$timeConstraint1, $timeConstraint2, $timeConstraint3]);

        $periods = $orTimeConstraint->getSequence(Period::fromDate('2021-01-01', '2021-01-06'));

        $this->assertEquals(new Sequence(
            Period::fromDate('2021-01-01', '2021-01-04'),
            Period::fromDate('2021-01-05', '2021-01-06')
        ), $periods);
    }
}