<?php

use League\Period\Bounds;
use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\OrTimeConstraint;

require_once __DIR__ . '/utils/createDateTime.php';
require_once __DIR__ . '/utils/createDuration.php';
require_once __DIR__ . '/utils/createSinglePeriodTimeConstraint.php';

class TimeConstraintGetClosestInstantTest extends TestCase
{

    private function createTimeConstraint()
    {
        //                       11  13  15
        // 0 1 2 3 4 5 6 7 8 9 10  12  14  16
        //   (---)   (---]     [---) [---]
        $time_constraint = new OrTimeConstraint([
            createSinglePeriodTimeConstraint(1, 3, Bounds::ExcludeAll),
            createSinglePeriodTimeConstraint(5, 7, Bounds::ExcludeStartIncludeEnd),
            createSinglePeriodTimeConstraint(10, 12, Bounds::IncludeStartExcludeEnd),
            createSinglePeriodTimeConstraint(13, 15, Bounds::IncludeAll),
        ]);
        return $time_constraint;
    }

    public function testGetClosestInstantWithPositiveDuration()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_period_duration = createDuration(3);

        $closestDates = [];
        for ($i = 0; $i <= 15; $i++) {
            $closestDates[] = $time_constraint->getClosestInstant(createDateTime($i), $search_period_duration);
        }

        //                       11  13  15
        // 0 1 2 3 4 5 6 7 8 9 10  12  14  16
        //   (---)   (---]     [---) [---]
        $this->assertEquals(createDateTime(1), $closestDates[0]);
        $this->assertEquals(createDateTime(1), $closestDates[1]);
        $this->assertEquals(createDateTime(2), $closestDates[2]);
        $this->assertEquals(createDateTime(5), $closestDates[3]);
        $this->assertEquals(createDateTime(5), $closestDates[4]);
        $this->assertEquals(createDateTime(5), $closestDates[5]);
        $this->assertEquals(createDateTime(6), $closestDates[6]);
        $this->assertEquals(createDateTime(7), $closestDates[7]);
        $this->assertEquals(createDateTime(10), $closestDates[8]);
        $this->assertEquals(createDateTime(10), $closestDates[9]);
        $this->assertEquals(createDateTime(10), $closestDates[10]);
        $this->assertEquals(createDateTime(11), $closestDates[11]);
        $this->assertEquals(createDateTime(13), $closestDates[12]);
        $this->assertEquals(createDateTime(13), $closestDates[13]);
        $this->assertEquals(createDateTime(14), $closestDates[14]);
        $this->assertEquals(createDateTime(15), $closestDates[15]);
    }

    public function testGetClosestInstantWithPositiveDurationAndException()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_period_duration = createDuration(3);

        $this->expectException(Exception::class);
        $time_constraint->getClosestInstant(createDateTime(16), $search_period_duration);
    }

    public function testGetClosestInstantWithNegativeDuration()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_period_duration = createDuration(-3);

        $closestDates = [null, null];
        for ($i = 2; $i < 12; $i++) {
            $closestDates[] = $time_constraint->getClosestInstant(createDateTime($i), $search_period_duration);
        }

        //                       11  13  15
        // 0 1 2 3 4 5 6 7 8 9 10  12  14  16
        //   (---)   (---]     [---) [---]
        $this->assertEquals(createDateTime(2), $closestDates[2]);
        $this->assertEquals(createDateTime(3), $closestDates[3]);
        $this->assertEquals(createDateTime(3), $closestDates[4]);
        $this->assertEquals(createDateTime(3), $closestDates[5]);
        $this->assertEquals(createDateTime(6), $closestDates[6]);
        $this->assertEquals(createDateTime(7), $closestDates[7]);
        $this->assertEquals(createDateTime(7), $closestDates[8]);
        $this->assertEquals(createDateTime(7), $closestDates[9]);
        $this->assertEquals(createDateTime(10), $closestDates[10]);
        $this->assertEquals(createDateTime(11), $closestDates[11]);
        $this->assertEquals(createDateTime(12), $closestDates[12]);
        $this->assertEquals(createDateTime(13), $closestDates[13]);
        $this->assertEquals(createDateTime(14), $closestDates[14]);
        $this->assertEquals(createDateTime(15), $closestDates[15]);
        $this->assertEquals(createDateTime(15), $closestDates[16]);
    }

    public function testGetClosestInstantWithNegativeDurationAndException1()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_period_duration = createDuration(-3);

        $this->expectException(Exception::class);
        $time_constraint->getClosestInstant(createDateTime(0), $search_period_duration);
    }
}