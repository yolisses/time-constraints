<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\V2\TimeInterval;
use Yolisses\TimeConstraints\V2\TimeIntervalOperations;

class TimeIntervalOperationsDifferenceTest extends TestCase
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
        $result = TimeIntervalOperations::difference([], [$interval]);
        $this->assertEmpty($result);
    }

    public function testEmptySecondArrayReturnsFirstArray()
    {
        $interval = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(2)
        );
        $result = TimeIntervalOperations::difference([$interval], []);
        $this->assertCount(1, $result);
        $this->assertEquals($interval->getStart(), $result[0]->getStart());
        $this->assertEquals($interval->getEnd(), $result[0]->getEnd());
    }

    public function testNonOverlappingIntervalsReturnsFirstArray()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(2)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(3),
            $this->createDateTime(4)
        );

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
    }

    public function testCompletelyOverlappingIntervalsReturnsEmptyArray()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(3)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(4)
        );

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertEmpty($result);
    }

    public function testPartiallyOverlappingIntervalsStart()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(3)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(4)
        );

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertCount(1, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getStart(), $result[0]->getEnd());
    }

    public function testPartiallyOverlappingIntervalsEnd()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(4)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(3)
        );

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertCount(1, $result);
        $this->assertEquals($interval2->getEnd(), $result[0]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[0]->getEnd());
    }

    public function testIntervalSplitByDifference()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(5)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(3)
        );

        $result = TimeIntervalOperations::difference([$interval1], [$interval2]);
        $this->assertCount(2, $result);
        $this->assertEquals($interval1->getStart(), $result[0]->getStart());
        $this->assertEquals($interval2->getStart(), $result[0]->getEnd());
        $this->assertEquals($interval2->getEnd(), $result[1]->getStart());
        $this->assertEquals($interval1->getEnd(), $result[1]->getEnd());
    }

    public function testMultipleIntervalsWithMultipleDifferences()
    {
        $interval1 = new TimeInterval(
            $this->createDateTime(1),
            $this->createDateTime(4)
        );
        $interval2 = new TimeInterval(
            $this->createDateTime(6),
            $this->createDateTime(8)
        );
        $interval3 = new TimeInterval(
            $this->createDateTime(2),
            $this->createDateTime(3)
        );
        $interval4 = new TimeInterval(
            $this->createDateTime(7),
            $this->createDateTime(9)
        );

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