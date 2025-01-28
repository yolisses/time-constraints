<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\OrTimeConstraint;
use Yolisses\TimeConstraints\Constraint\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class OrTimeConstraintTest extends TestCase
{
    public function testGetIntervals()
    {
        $time_constraint1 = $this->createMock(TimeConstraint::class);
        $time_constraint2 = $this->createMock(TimeConstraint::class);
        $time_constraint3 = $this->createMock(TimeConstraint::class);

        $time_constraint1->method('getIntervals')->willReturn([
            TimeInterval::fromStrings('2021-01-01 00:00:00', '2021-01-01 01:00:00')
        ]);

        $time_constraint2->method('getIntervals')->willReturn([
            TimeInterval::fromStrings('2021-01-01 00:30:00', '2021-01-01 01:30:00')
        ]);

        $time_constraint3->method('getIntervals')->willReturn([
            TimeInterval::fromStrings('2021-01-01 01:45:00', '2021-01-01 02:00:00')
        ]);

        $and_time_constraint = new OrTimeConstraint([$time_constraint1, $time_constraint2, $time_constraint3]);

        $intervals = $and_time_constraint->getIntervals(new DateTimeImmutable('2021-01-01 00:00:00'), new DateTimeImmutable('2021-01-01 02:00:00'));

        $this->assertEquals([
            TimeInterval::fromStrings('2021-01-01 00:00:00', '2021-01-01 01:30:00'),
            TimeInterval::fromStrings('2021-01-01 01:45:00', '2021-01-01 02:00:00')
        ], $intervals);
    }
}