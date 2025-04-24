<?php

use Yolisses\TimeConstraints\Interval\TimeIntervalOperations;

require_once __DIR__ . '/TimeIntervalOperationsTestBase.php';

class TimeIntervalOperationsIntersectionTest extends TimeIntervalOperationsTestBase
{
    public function testEmptyFirstArrayReturnsEmptyArray()
    {
        $interval = $this->createInterval(1, 2);
        $result = TimeIntervalOperations::intersection([], [$interval]);
        $this->assertEmpty($result);
    }

    public function testEmptySecondArrayReturnsEmptyArray()
    {
        $interval = $this->createInterval(1, 2);
        $result = TimeIntervalOperations::intersection([$interval], []);
        $this->assertEmpty($result);
    }

    public function testNonOverlappingIntervals()
    {
        $interval1 = $this->createInterval(1, 2);
        $interval2 = $this->createInterval(3, 4);

        $result = TimeIntervalOperations::intersection([$interval1], [$interval2]);
        $this->assertEmpty($result);
    }

    public function testCompletelyOverlappingIntervals()
    {
        $interval1 = $this->createInterval(1, 4);
        $interval2 = $this->createInterval(2, 3);

        $result = TimeIntervalOperations::intersection([$interval1], [$interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval2->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
    }

    public function testPartiallyOverlappingIntervals()
    {
        $interval1 = $this->createInterval(1, 3);
        $interval2 = $this->createInterval(2, 4);

        $result = TimeIntervalOperations::intersection([$interval1], [$interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval2->getStart(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
    }

    public function testAdjacentIntervals()
    {
        $interval1 = $this->createInterval(1, 2);
        $interval2 = $this->createInterval(2, 3);

        $result = TimeIntervalOperations::intersection([$interval1], [$interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval2->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getStart(), $result[0]->getEnd());
    }

    public function testMultipleIntervalsWithMultipleIntersections()
    {
        $interval1 = $this->createInterval(1, 3);
        $interval2 = $this->createInterval(5, 7);
        $interval3 = $this->createInterval(2, 6);

        $result = TimeIntervalOperations::intersection([$interval1, $interval2], [$interval3]);

        $this->assertCount(2, $result);
        $this->assertEquals($interval3->getStart(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
        $this->assertEquals($interval2->getStart(), $result[1]->getStart());
        $this->assertEquals($interval3->getEnd(), $result[1]->getEnd());
    }

    public function testMultipleOverlappingIntersections()
    {
        $interval1 = $this->createInterval(1, 4);
        $interval2 = $this->createInterval(2, 5);
        $interval3 = $this->createInterval(3, 6);

        $result = TimeIntervalOperations::intersection([$interval1, $interval2], [$interval3]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval3->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
    }
}
?>