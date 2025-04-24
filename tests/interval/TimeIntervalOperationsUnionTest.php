<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TimeIntervalOperations;

class TimeIntervalOperationsUnionTest extends TestCase
{
    public function testEmptyArrayReturnsEmptyArray()
    {
        $result = TimeIntervalOperations::union([]);
        $this->assertEmpty($result);
    }

    public function testSingleIntervalReturnsSameInterval()
    {
        $interval = createTimeInterval(1, 2);

        $result = TimeIntervalOperations::union([$interval]);

        $this->assertCount(1, $result);
        $this->assertSame($interval->getStart(), $result[0]->getStart());
        $this->assertSame($interval->getEnd(), $result[0]->getEnd());
    }

    public function testNonOverlappingIntervals()
    {
        $interval1 = createTimeInterval(1, 2);
        $interval2 = createTimeInterval(3, 4);

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(2, $result);
        $this->assertSame($interval1->getStart(), $result[0]->getStart());
        $this->assertSame($interval1->getEnd(), $result[0]->getEnd());
        $this->assertSame($interval2->getStart(), $result[1]->getStart());
        $this->assertSame($interval2->getEnd(), $result[1]->getEnd());
    }

    public function testCompletelyOverlappingIntervals()
    {
        $interval1 = createTimeInterval(1, 4);
        $interval2 = createTimeInterval(2, 3);

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
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