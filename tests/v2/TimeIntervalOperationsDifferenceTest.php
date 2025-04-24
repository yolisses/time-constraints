<?php

require_once __DIR__ . '/TimeIntervalOperationsTestBase.php';

use Yolisses\TimeConstraints\V2\TimeIntervalOperations;

class TimeIntervalOperationsDifferenceTest extends TimeIntervalOperationsTestBase
{
    public function testEmptyFirstArrayReturnsEmptyArray()
    {
        $interval = $this->createInterval(1, 2);
        $result = TimeIntervalOperations::difference([], [$interval]);
        $this->assertEmpty($result);
    }

    public function testEmptySecondArrayReturnsFirstArray()
    {
        $interval = $this->createInterval(1, 2);
        $result = TimeIntervalOperations::difference([$interval], []);
        $this->assertCount(1, $result);
        $this->assertEquals($interval->getStart(), $result[0]->getStart());
        $this->assertEquals($interval->getEnd(), $result[0]->getEnd());
    }

    public function testNonOverlappingIntervalsReturnsFirstArray()
    {
        $interval1 = $this->createInterval(1, 2);
        $interval2 = $this->createInterval(3, 4);

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
    }

    public function testCompletelyOverlappingIntervalsReturnsEmptyArray()
    {
        $interval1 = $this->createInterval(2, 3);
        $interval2 = $this->createInterval(1, 4);

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertEmpty($result);
    }

    public function testPartiallyOverlappingIntervalsStart()
    {
        $interval1 = $this->createInterval(1, 3);
        $interval2 = $this->createInterval(2, 4);

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getStart(), $result[0]->getEnd());
    }

    public function testPartiallyOverlappingIntervalsEnd()
    {
        $interval1 = $this->createInterval(2, 4);
        $interval2 = $this->createInterval(1, 3);

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertCount(1, $result);
        $this->assertEquals($interval2->getEnd(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
    }

    public function testIntervalSplitByDifference()
    {
        $interval1 = $this->createInterval(1, 5);
        $interval2 = $this->createInterval(2, 3);

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertCount(2, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getStart(), $result[0]->getEnd());
        $this->assertEquals($interval2->getEnd(), $result[1]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[1]->getEnd());
    }

    public function testMultipleIntervalsWithMultipleDifferences()
    {
        $interval1 = $this->createInterval(1, 4);
        $interval2 = $this->createInterval(6, 8);
        $interval3 = $this->createInterval(2, 3);
        $interval4 = $this->createInterval(7, 9);

        $result = TimeIntervalOperations::difference([$interval1, $interval2], [$interval3, $interval4]);
        $this->assertCount(3, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval3->getStart(), $result[0]->getEnd());
        $this->assertEquals($interval3->getEnd(), $result[1]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[1]->getEnd());
        $this->assertEquals($interval2->getStart(), $result[2]->getStart());
        $this->assertEquals($interval4->getStart(), $result[2]->getEnd());
    }
}