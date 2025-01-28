<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Constraint\OrTimeConstraint;

require_once __DIR__ . '/../utils/createInstant.php';
require_once __DIR__ . '/../utils/createDuration.php';
require_once __DIR__ . '/../utils/createSingleIntervalTimeConstraint.php';

class TimeConstraintGetClosestInstantTest extends TestCase
{

    private function createTimeConstraint()
    {
        //                       11  13  
        // 0 1 2 3 4 5 6 7 8 9 10  12
        //   ████    ████      ████
        $time_constraint = new OrTimeConstraint([
            createSingleIntervalTimeConstraint(1, 3),
            createSingleIntervalTimeConstraint(5, 7),
            createSingleIntervalTimeConstraint(10, 12)
        ]);
        return $time_constraint;
    }

    public function testGetClosestInstantWithPositiveDuration()
    {
        //                       11  13  
        // 0 1 2 3 4 5 6 7 8 9 10  12
        //   ████    ████      ████
        $time_constraint = $this->createTimeConstraint();
        $search_interval_duration = createDuration(3);

        $this->assertEquals(createInstant(1), $time_constraint->getClosestInstant(createInstant(0), $search_interval_duration));
        $this->assertEquals(createInstant(1), $time_constraint->getClosestInstant(createInstant(1), $search_interval_duration));
        $this->assertEquals(createInstant(2), $time_constraint->getClosestInstant(createInstant(2), $search_interval_duration));
        $this->assertEquals(createInstant(5), $time_constraint->getClosestInstant(createInstant(3), $search_interval_duration));
        $this->assertEquals(createInstant(5), $time_constraint->getClosestInstant(createInstant(4), $search_interval_duration));
        $this->assertEquals(createInstant(5), $time_constraint->getClosestInstant(createInstant(5), $search_interval_duration));
        $this->assertEquals(createInstant(6), $time_constraint->getClosestInstant(createInstant(6), $search_interval_duration));
        $this->assertEquals(createInstant(10), $time_constraint->getClosestInstant(createInstant(7), $search_interval_duration));
        $this->assertEquals(createInstant(10), $time_constraint->getClosestInstant(createInstant(8), $search_interval_duration));
        $this->assertEquals(createInstant(10), $time_constraint->getClosestInstant(createInstant(9), $search_interval_duration));
        $this->assertEquals(createInstant(10), $time_constraint->getClosestInstant(createInstant(10), $search_interval_duration));
        $this->assertEquals(createInstant(11), $time_constraint->getClosestInstant(createInstant(11), $search_interval_duration));
    }

    public function testGetClosestInstantWithPositiveDurationAndException1()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_interval_duration = createDuration(3);

        $this->expectException(Exception::class);
        $time_constraint->getClosestInstant(createInstant(12), $search_interval_duration);
    }

    public function testGetClosestInstantWithPositiveDurationAndException2()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_interval_duration = createDuration(3);

        $this->expectException(Exception::class);
        $time_constraint->getClosestInstant(createInstant(13), $search_interval_duration);
    }

    public function testGetClosestInstantWithNegativeDuration()
    {
        //                       11  13  
        // 0 1 2 3 4 5 6 7 8 9 10  12
        //   ████    ████      ████
        $time_constraint = $this->createTimeConstraint();
        $search_interval_duration = createDuration(-3);

        $this->assertEquals(createInstant(2), $time_constraint->getClosestInstant(createInstant(2), $search_interval_duration));
        $this->assertEquals(createInstant(3), $time_constraint->getClosestInstant(createInstant(3), $search_interval_duration));
        $this->assertEquals(createInstant(3), $time_constraint->getClosestInstant(createInstant(4), $search_interval_duration));
        $this->assertEquals(createInstant(3), $time_constraint->getClosestInstant(createInstant(5), $search_interval_duration));
        $this->assertEquals(createInstant(6), $time_constraint->getClosestInstant(createInstant(6), $search_interval_duration));
        $this->assertEquals(createInstant(7), $time_constraint->getClosestInstant(createInstant(7), $search_interval_duration));
        $this->assertEquals(createInstant(7), $time_constraint->getClosestInstant(createInstant(8), $search_interval_duration));
        $this->assertEquals(createInstant(7), $time_constraint->getClosestInstant(createInstant(9), $search_interval_duration));
        $this->assertEquals(createInstant(7), $time_constraint->getClosestInstant(createInstant(10), $search_interval_duration));
        $this->assertEquals(createInstant(11), $time_constraint->getClosestInstant(createInstant(11), $search_interval_duration));
        $this->assertEquals(createInstant(12), $time_constraint->getClosestInstant(createInstant(12), $search_interval_duration));
        $this->assertEquals(createInstant(12), $time_constraint->getClosestInstant(createInstant(13), $search_interval_duration));
    }

    public function testGetClosestInstantWithNegativeDurationAndException1()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_interval_duration = createDuration(-3);

        $this->expectException(Exception::class);
        $time_constraint->getClosestInstant(createInstant(0), $search_interval_duration);
    }

    public function testGetClosestInstantWithNegativeDurationAndException2()
    {
        $time_constraint = $this->createTimeConstraint();
        $search_interval_duration = createDuration(-3);

        $this->expectException(Exception::class);
        $time_constraint->getClosestInstant(createInstant(1), $search_interval_duration);
    }
}
?>