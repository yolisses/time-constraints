<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\OrTimeConstraint;

require_once __DIR__ . '/utils/createDateTime.php';
require_once __DIR__ . '/utils/createDuration.php';
require_once __DIR__ . '/utils/createSinglePeriodTimeConstraint.php';

class TimeConstraintGetClosestInstantTest extends TestCase
{

    private function createTimeConstraint()
    {
        //                       11  13  
        // 0 1 2 3 4 5 6 7 8 9 10  12
        //   [---)   [---)     [---)
        $time_constraint = new OrTimeConstraint([
            createSinglePeriodTimeConstraint(1, 3),
            createSinglePeriodTimeConstraint(5, 7),
            createSinglePeriodTimeConstraint(10, 12)
        ]);
        return $time_constraint;
    }

    public function testGetClosestInstantWithPositiveDuration()
    {
        //                       11  13  
        // 0 1 2 3 4 5 6 7 8 9 10  12
        //   [---)   [---)     [---)
        $time_constraint = $this->createTimeConstraint();
        $search_period_duration = createDuration(3);

        $this->assertEquals(createDateTime(1), $time_constraint->getClosestInstant(createDateTime(0), $search_period_duration));
        $this->assertEquals(createDateTime(1), $time_constraint->getClosestInstant(createDateTime(1), $search_period_duration));
        $this->assertEquals(createDateTime(2), $time_constraint->getClosestInstant(createDateTime(2), $search_period_duration));
        $this->assertEquals(createDateTime(5), $time_constraint->getClosestInstant(createDateTime(3), $search_period_duration));
        $this->assertEquals(createDateTime(5), $time_constraint->getClosestInstant(createDateTime(4), $search_period_duration));
        $this->assertEquals(createDateTime(5), $time_constraint->getClosestInstant(createDateTime(5), $search_period_duration));
        $this->assertEquals(createDateTime(6), $time_constraint->getClosestInstant(createDateTime(6), $search_period_duration));
        $this->assertEquals(createDateTime(10), $time_constraint->getClosestInstant(createDateTime(7), $search_period_duration));
        $this->assertEquals(createDateTime(10), $time_constraint->getClosestInstant(createDateTime(8), $search_period_duration));
        $this->assertEquals(createDateTime(10), $time_constraint->getClosestInstant(createDateTime(9), $search_period_duration));
        $this->assertEquals(createDateTime(10), $time_constraint->getClosestInstant(createDateTime(10), $search_period_duration));
        $this->assertEquals(createDateTime(11), $time_constraint->getClosestInstant(createDateTime(11), $search_period_duration));
    }

    public function testGetClosestInstantWithPositiveDurationAndException()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_period_duration = createDuration(3);

        $this->expectException(Exception::class);
        $time_constraint->getClosestInstant(createDateTime(13), $search_period_duration);
    }

    public function testGetClosestInstantWithNegativeDuration()
    {
        //                       11  13  
        // 0 1 2 3 4 5 6 7 8 9 10  12
        //   [---)   [---)     [---)
        $time_constraint = $this->createTimeConstraint();
        $search_period_duration = createDuration(-3);

        $this->assertEquals(createDateTime(2), $time_constraint->getClosestInstant(createDateTime(2), $search_period_duration));
        $this->assertEquals(createDateTime(3), $time_constraint->getClosestInstant(createDateTime(3), $search_period_duration));
        $this->assertEquals(createDateTime(3), $time_constraint->getClosestInstant(createDateTime(4), $search_period_duration));
        $this->assertEquals(createDateTime(5), $time_constraint->getClosestInstant(createDateTime(5), $search_period_duration));
        $this->assertEquals(createDateTime(6), $time_constraint->getClosestInstant(createDateTime(6), $search_period_duration));
        $this->assertEquals(createDateTime(7), $time_constraint->getClosestInstant(createDateTime(7), $search_period_duration));
        $this->assertEquals(createDateTime(7), $time_constraint->getClosestInstant(createDateTime(8), $search_period_duration));
        $this->assertEquals(createDateTime(7), $time_constraint->getClosestInstant(createDateTime(9), $search_period_duration));
        $this->assertEquals(createDateTime(10), $time_constraint->getClosestInstant(createDateTime(10), $search_period_duration));
        $this->assertEquals(createDateTime(11), $time_constraint->getClosestInstant(createDateTime(11), $search_period_duration));
        $this->assertEquals(createDateTime(12), $time_constraint->getClosestInstant(createDateTime(12), $search_period_duration));
        $this->assertEquals(createDateTime(12), $time_constraint->getClosestInstant(createDateTime(13), $search_period_duration));
    }

    public function testGetClosestInstantWithNegativeDurationAndException1()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_period_duration = createDuration(-3);

        $this->expectException(Exception::class);
        $time_constraint->getClosestInstant(createDateTime(0), $search_period_duration);
    }
}