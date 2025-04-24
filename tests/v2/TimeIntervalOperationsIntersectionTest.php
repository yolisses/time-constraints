<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\V2\TimeInterval;
use Yolisses\TimeConstraints\V2\TimeIntervalOperations;

class TimeIntervalOperationsIntersectionTest extends TestCase
{
    private function createDateTime(int $second): DateTimeImmutable
    {
        return new DateTimeImmutable("2023-01-01 00:00:$second");
    }

    public function testEmptyFirstArrayReturnsEmptyArray()
    {
        $interval = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(2)
        );
        $result = TimeIntervalOperations::intersection([], [$interval]);
        $this->assertEmpty($result);
    }

    public function testEmptySecondArrayReturnsEmptyArray()
    {
        $interval = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(2)
        );
        $result = TimeIntervalOperations::intersection([$interval], []);
        $this->assertEmpty($result);
    }

    public function testNonOverlappingIntervals()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(2)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(3),
            $this->createDateTime(4)
        );

        $result = TimeIntervalOperations::intersection([$interval1], [$interval2]);
        $this->assertEmpty($result);
    }

    public function testCompletelyOverlappingIntervals()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(4)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(3)
        );

        $result = TimeIntervalOperations::intersection([$interval1], [$interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval2->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
    }

    public function testPartiallyOverlappingIntervals()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(3)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(4)
        );

        $result = TimeIntervalOperations::intersection([$interval1], [$interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval2->getStart(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
    }

    public function testAdjacentIntervals()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(2)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(3)
        );

        $result = TimeIntervalOperations::intersection([$interval1], [$interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval2->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getStart(), $result[0]->getEnd());
    }

    public function testMultipleIntervalsWithMultipleIntersections()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(3)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(5),
            $this->createDateTime(7)
        );
        $interval3 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(6)
        );

        $result = TimeIntervalOperations::intersection([$interval1, $interval2], [$interval3]);

        $this->assertCount(2, $result);
        $this->assertEquals($interval3->getStart(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
        $this->assertEquals($interval2->getStart(), $result[1]->getStart());
        $this->assertEquals($interval3->getEnd(), $result[1]->getEnd());
    }

    public function testMultipleOverlappingIntersections()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(4)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(5)
        );
        $interval3 = new TimeInterval(
            $this->createDateTime(3),
            $this->createDateTime(6)
        );

        $result = TimeIntervalOperations::intersection([$interval1, $interval2], [$interval3]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval3->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
    }
}
?>