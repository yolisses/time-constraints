<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\V2\TimeInterval;
use Yolisses\TimeConstraints\V2\TimeIntervalOperations;

class TimeIntervalOperationsUnionTest extends TestCase
{
    private function createDateTime(int $second): DateTimeImmutable
    {
        return new DateTimeImmutable("2023-01-01 00:00:$second");
    }

    public function testEmptyArrayReturnsEmptyArray()
    {
        $result = TimeIntervalOperations::union([]);
        $this->assertEmpty($result);
    }

    public function testSingleIntervalReturnsSameInterval()
    {
        $interval = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(2),
        );

        $result = TimeIntervalOperations::union([$interval]);

        $this->assertCount(1, $result);
        $this->assertSame($interval->getStart(), $result[0]->getStart());
        $this->assertSame($interval->getEnd(), $result[0]->getEnd());
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

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(2, $result);
        $this->assertSame($interval1->getStart(), $result[0]->getStart());
        $this->assertSame($interval1->getEnd(), $result[0]->getEnd());
        $this->assertSame($interval2->getStart(), $result[1]->getStart());
        $this->assertSame($interval2->getEnd(), $result[1]->getEnd());
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

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
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

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
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

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
    }

    public function testMultipleOverlappingAndNonOverlappingIntervals()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(3)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(4)
        );
        $interval3 = new TimeInterval(
            $this->createDateTime(5),
            $this->createDateTime(6)
        );

        $result = TimeIntervalOperations::union([$interval1, $interval2, $interval3]);

        $this->assertCount(2, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getEnd(), $result[0]->getEnd());
        $this->assertEquals($interval3->getStart(), $result[1]->getStart());
        $this->assertEquals($interval3->getEnd(), $result[1]->getEnd());
    }
}