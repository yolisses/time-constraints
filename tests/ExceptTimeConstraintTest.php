<?php

use League\Period\Period;
use League\Period\Sequence;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\ExceptTimeConstraint;
use Yolisses\TimeConstraints\TimeConstraint;

require_once __DIR__ . '/utils/createDateTime.php';
require_once __DIR__ . '/utils/createPeriod.php';

class ExceptTimeConstraintTest extends TestCase
{
    public function testgetSequenceEmpty()
    {
        $timeConstraint1 = $this->createMock(TimeConstraint::class);
        $timeConstraint2 = $this->createMock(TimeConstraint::class);

        $timeConstraint1->method('getSequence')->willReturn(new Sequence());
        $timeConstraint2->method('getSequence')->willReturn(new Sequence());

        $exceptTimeConstraint = new ExceptTimeConstraint($timeConstraint1, $timeConstraint2);

        $clampPeriod = Period::fromDate(createDateTime(1), createDateTime(2));
        $periods = $exceptTimeConstraint->getSequence($clampPeriod);

        $this->assertEquals(new Sequence(), $periods);
    }

    public function testgetSequenceWithOneConstraint()
    {

        $timeConstraint1 = $this->createMock(TimeConstraint::class);
        $timeConstraint2 = $this->createMock(TimeConstraint::class);

        $timeConstraint1->method('getSequence')->willReturn(new Sequence(
            createPeriod(1, 2)
        ));
        $timeConstraint2->method('getSequence')->willReturn(new Sequence());

        $exceptTimeConstraint = new ExceptTimeConstraint($timeConstraint1, $timeConstraint2);

        $clampPeriod = Period::fromDate(createDateTime(0), createDateTime(2));
        $periods = $exceptTimeConstraint->getSequence($clampPeriod);

        $this->assertEquals(new Sequence(
            createPeriod(1, 2)
        ), $periods);
    }

    public function testgetSequence()
    {
        $timeConstraint1 = $this->createMock(TimeConstraint::class);
        $timeConstraint2 = $this->createMock(TimeConstraint::class);

        $timeConstraint1->method('getSequence')->willReturn(new Sequence(
            createPeriod(1, 3),
            createPeriod(5, 6),
        ));

        $timeConstraint2->method('getSequence')->willReturn(new Sequence(
            createPeriod(2, 4),
        ));

        $exceptTimeConstraint = new ExceptTimeConstraint($timeConstraint1, $timeConstraint2);

        $clampPeriod = Period::fromDate(createDateTime(1), createDateTime(6));
        $periods = $exceptTimeConstraint->getSequence($clampPeriod);

        $this->assertEquals(new Sequence(
            createPeriod(1, 2),
            createPeriod(5, 6),
        ), $periods);
    }
}