<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\ExceptTimeConstraint;
use Yolisses\TimeConstraints\TimeConstraint;

require_once __DIR__ . '/../utils/createDateTime.php';
require_once __DIR__ . '/../utils/createTimePeriod.php';

class ExceptTimeConstraintTest extends TestCase
{
    public function testGetPeriodsEmpty()
    {
        $time_constraint_1 = $this->createMock(TimeConstraint::class);
        $time_constraint_2 = $this->createMock(TimeConstraint::class);

        $time_constraint_1->method('getPeriods')->willReturn([]);
        $time_constraint_2->method('getPeriods')->willReturn([]);

        $except_time_constraint = new ExceptTimeConstraint($time_constraint_1, $time_constraint_2);

        $periods = $except_time_constraint->getPeriods(createDateTime(1), createDateTime(2));

        $this->assertEquals([], $periods);
    }

    public function testGetPeriodsWithOneConstraint()
    {

        $time_constraint_1 = $this->createMock(TimeConstraint::class);
        $time_constraint_2 = $this->createMock(TimeConstraint::class);

        $time_constraint_1->method('getPeriods')->willReturn([
            createTimePeriod(1, 2)
        ]);
        $time_constraint_2->method('getPeriods')->willReturn([]);

        $except_time_constraint = new ExceptTimeConstraint($time_constraint_1, $time_constraint_2);

        $periods = $except_time_constraint->getPeriods(createDateTime(0), createDateTime(2));

        $this->assertEquals([
            createTimePeriod(1, 2)
        ], $periods);
    }

    public function testGetPeriods()
    {
        $time_constraint_1 = $this->createMock(TimeConstraint::class);
        $time_constraint_2 = $this->createMock(TimeConstraint::class);

        $time_constraint_1->method('getPeriods')->willReturn([
            createTimePeriod(1, 3),
            createTimePeriod(5, 6),
        ]);

        $time_constraint_2->method('getPeriods')->willReturn([
            createTimePeriod(2, 4),
        ]);

        $except_time_constraint = new ExceptTimeConstraint($time_constraint_1, $time_constraint_2);

        $periods = $except_time_constraint->getPeriods(createDateTime(1), createDateTime(6));

        $this->assertEquals([
            createTimePeriod(1, 2),
            createTimePeriod(5, 6),
        ], $periods);
    }
}