<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\AndTimeConstraint;
use Yolisses\TimeConstraints\Constraint\TimeConstraint;

class AndTimeConstraintTest extends TestCase
{
    public function testGetIntervalsEmpty()
    {
        $and_time_constraint = new AndTimeConstraint([]);

        $intervals = $and_time_constraint->getIntervals(createDateTime(1), createDateTime(2));

        $this->assertEquals([], $intervals);
    }

    public function testGetIntervalsWithOneConstraint()
    {
        $time_constraint1 = $this->createMock(TimeConstraint::class);

        $time_constraint1->method('getIntervals')->willReturn([
            createTimeInterval(1, 2)
        ]);

        $and_time_constraint = new AndTimeConstraint([$time_constraint1]);

        $intervals = $and_time_constraint->getIntervals(createDateTime(0), createDateTime(2));

        $this->assertEquals([
            createTimeInterval(1, 2)
        ], $intervals);
    }

    public function testGetIntervals()
    {
        $time_constraint1 = $this->createMock(TimeConstraint::class);
        $time_constraint2 = $this->createMock(TimeConstraint::class);
        $time_constraint3 = $this->createMock(TimeConstraint::class);

        $time_constraint1->method('getIntervals')->willReturn([
            createTimeInterval(1, 4),
        ]);

        $time_constraint2->method('getIntervals')->willReturn([
            createTimeInterval(2, 5),
        ]);

        $time_constraint3->method('getIntervals')->willReturn([
            createTimeInterval(1, 4),
            createTimeInterval(3, 6),
        ]);

        $and_time_constraint = new AndTimeConstraint([$time_constraint1, $time_constraint2, $time_constraint3]);

        $intervals = $and_time_constraint->getIntervals(createDateTime(1), createDateTime(6));

        $this->assertEquals([
            createTimeInterval(3, 4)
        ], $intervals);
    }
}