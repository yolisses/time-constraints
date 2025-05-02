<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\OrTimeConstraint;
use Yolisses\TimeConstraints\TimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriod;

class OrTimeConstraintTest extends TestCase
{
    public function testGetPeriods()
    {
        $time_constraint1 = $this->createMock(TimeConstraint::class);
        $time_constraint2 = $this->createMock(TimeConstraint::class);
        $time_constraint3 = $this->createMock(TimeConstraint::class);

        $time_constraint1->method('getPeriods')->willReturn([
            TimePeriod::fromStrings('2021-01-01 00:00:00', '2021-01-01 01:00:00')
        ]);

        $time_constraint2->method('getPeriods')->willReturn([
            TimePeriod::fromStrings('2021-01-01 00:30:00', '2021-01-01 01:30:00')
        ]);

        $time_constraint3->method('getPeriods')->willReturn([
            TimePeriod::fromStrings('2021-01-01 01:45:00', '2021-01-01 02:00:00')
        ]);

        $and_time_constraint = new OrTimeConstraint([$time_constraint1, $time_constraint2, $time_constraint3]);

        $periods = $and_time_constraint->getPeriods(new DateTimeImmutable('2021-01-01 00:00:00'), new DateTimeImmutable('2021-01-01 02:00:00'));

        $this->assertEquals([
            TimePeriod::fromStrings('2021-01-01 00:00:00', '2021-01-01 01:30:00'),
            TimePeriod::fromStrings('2021-01-01 01:45:00', '2021-01-01 02:00:00')
        ], $periods);
    }
}