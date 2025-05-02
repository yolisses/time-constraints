<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\ExceptTimeConstraint;
use Yolisses\TimeConstraints\TimeConstraint;

require_once __DIR__ . '/../utils/createDateTime.php';
require_once __DIR__ . '/../utils/createTimeInterval.php';

class ExceptTimeConstraintTest extends TestCase
{
    public function testGetIntervalsEmpty()
    {
        $time_constraint_1 = $this->createMock(TimeConstraint::class);
        $time_constraint_2 = $this->createMock(TimeConstraint::class);

        $time_constraint_1->method('getIntervals')->willReturn([]);
        $time_constraint_2->method('getIntervals')->willReturn([]);

        $except_time_constraint = new ExceptTimeConstraint($time_constraint_1, $time_constraint_2);

        $intervals = $except_time_constraint->getIntervals(createDateTime(1), createDateTime(2));

        $this->assertEquals([], $intervals);
    }

    public function testGetIntervalsWithOneConstraint()
    {

        $time_constraint_1 = $this->createMock(TimeConstraint::class);
        $time_constraint_2 = $this->createMock(TimeConstraint::class);

        $time_constraint_1->method('getIntervals')->willReturn([
            createTimeInterval(1, 2)
        ]);
        $time_constraint_2->method('getIntervals')->willReturn([]);

        $except_time_constraint = new ExceptTimeConstraint($time_constraint_1, $time_constraint_2);

        $intervals = $except_time_constraint->getIntervals(createDateTime(0), createDateTime(2));

        $this->assertEquals([
            createTimeInterval(1, 2)
        ], $intervals);
    }

    public function testGetIntervals()
    {
        $time_constraint_1 = $this->createMock(TimeConstraint::class);
        $time_constraint_2 = $this->createMock(TimeConstraint::class);

        $time_constraint_1->method('getIntervals')->willReturn([
            createTimeInterval(1, 3),
            createTimeInterval(5, 6),
        ]);

        $time_constraint_2->method('getIntervals')->willReturn([
            createTimeInterval(2, 4),
        ]);

        $except_time_constraint = new ExceptTimeConstraint($time_constraint_1, $time_constraint_2);

        $intervals = $except_time_constraint->getIntervals(createDateTime(1), createDateTime(6));

        $this->assertEquals([
            createTimeInterval(1, 2),
            createTimeInterval(5, 6),
        ], $intervals);
    }
}