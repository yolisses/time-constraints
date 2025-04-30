<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TimeIntervalOperations;

require_once __DIR__ . '/../utils/createTimeInterval.php';

class TimeIntervalOperationsUnionTest extends TestCase
{
    public function testEmptyArrayReturnsEmptyArray()
    {
        $result = TimeIntervalOperations::union([]);
        $this->assertEmpty($result);
    }

    public function testSingleInterval()
    {
        $interval = createTimeInterval(1, 2, false, true);

        $result = TimeIntervalOperations::union([$interval]);

        $this->assertEquals([
            createTimeInterval(1, 2, false, true),
        ], $result);
    }

    public function testNonOverlappingIntervals()
    {
        $interval1 = createTimeInterval(1, 2, true, false);
        $interval2 = createTimeInterval(3, 4, true, false);

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertEquals([
            createTimeInterval(1, 2, true, false),
            createTimeInterval(3, 4, true, false),
        ], $result);
    }

    public function testCompletelyOverlappingIntervals()
    {
        $interval1 = createTimeInterval(2, 3, true, true);
        $interval2 = createTimeInterval(1, 4, false, false);

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertEquals([
            createTimeInterval(1, 4, false, false),
        ], $result);
    }

    public function testPartiallyOverlappingIntervals()
    {
        $interval1 = createTimeInterval(1, 3);
        $interval2 = createTimeInterval(2, 4);

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
    }

    public function testAdjacentIntervals()
    {
        $interval1 = createTimeInterval(1, 2);
        $interval2 = createTimeInterval(2, 3);

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
    }

    public function testMultipleOverlappingAndNonOverlappingIntervals()
    {
        $interval1 = createTimeInterval(1, 3);
        $interval2 = createTimeInterval(2, 4);
        $interval3 = createTimeInterval(5, 6);

        $result = TimeIntervalOperations::union([$interval1, $interval2, $interval3]);

        $this->assertCount(2, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
        $this->assertEquals($interval3->getStart(), $result[1]->getStart());
        $this->assertEquals($interval3->getEnd(), $result[1]->getEnd());
    }
}