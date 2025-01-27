<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\AnyTimeTimeConstraint;
use Yolisses\TimeConstraints\Constraint\SingleDayTimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class AnyTimeTimeConstraintTest extends TestCase
{
    public function testGetIntervals()
    {
        $constraint = new AnyTimeTimeConstraint();

        $start_instant = new DateTime('2025-01-01 02:03:04');
        $end_instant = new DateTime('2025-01-03 05:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([new TimeInterval($start_instant, $end_instant)], $intervals);
    }

    public function testGetIntervals2()
    {
        $day = new DateTime('2025-01-02 00:00:00');
        $constraint = new SingleDayTimeConstraint($day);

        $start_instant = new DateTime('2025-01-02 02:03:04');
        $end_instant = new DateTime('2025-01-02 05:06:07');

        $intervals = $constraint->getIntervals($start_instant, $end_instant);
        $this->assertEquals([new TimeInterval($start_instant, $end_instant)], $intervals);
    }
}