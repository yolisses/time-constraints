<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\AndTimeConstraint;
use Yolisses\TimeConstraints\TimeConstraint;

require_once __DIR__ . '/utils/createDateTime.php';

class AndTimeConstraintTest extends TestCase
{
    public function testGetPeriodsEmpty()
    {
        $and_time_constraint = new AndTimeConstraint([]);

        $periods = $and_time_constraint->getSequence(createDateTime(1), createDateTime(2));

        $this->assertEquals([], $periods);
    }

    public function testGetPeriodsWithOneConstraint()
    {
        $time_constraint1 = $this->createMock(TimeConstraint::class);

        $time_constraint1->method('getPeriods')->willReturn([
            createPeriod(1, 2)
        ]);

        $and_time_constraint = new AndTimeConstraint([$time_constraint1]);

        $periods = $and_time_constraint->getSequence(createDateTime(0), createDateTime(2));

        $this->assertEquals([
            createPeriod(1, 2)
        ], $periods);
    }

    public function testGetPeriods()
    {
        $time_constraint1 = $this->createMock(TimeConstraint::class);
        $time_constraint2 = $this->createMock(TimeConstraint::class);
        $time_constraint3 = $this->createMock(TimeConstraint::class);

        $time_constraint1->method('getPeriods')->willReturn([
            createPeriod(1, 4),
        ]);

        $time_constraint2->method('getPeriods')->willReturn([
            createPeriod(2, 5),
        ]);

        $time_constraint3->method('getPeriods')->willReturn([
            createPeriod(3, 6),
        ]);

        $and_time_constraint = new AndTimeConstraint([$time_constraint1, $time_constraint2, $time_constraint3]);

        $periods = $and_time_constraint->getSequence(createDateTime(1), createDateTime(6));

        $this->assertEquals([
            createPeriod(3, 4)
        ], $periods);
    }
}