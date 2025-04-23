<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\V2\TimeInterval;
use Yolisses\TimeConstraints\V2\TimeIntervalOperations;

class TimeIntervalOperationsUnionTest extends TestCase
{
    public function testEmptyArrayReturnsEmptyArray()
    {
        $result = TimeIntervalOperations::union([]);
        $this->assertEmpty($result);
    }

    public function testSingleIntervalReturnsSameInterval()
    {
        $interval = new TimeInterval(
            new DateTimeImmutable('2023-01-01 10:00:00'),
            new DateTimeImmutable('2023-01-01 12:00:00')
        );

        $result = TimeIntervalOperations::union([$interval]);

        $this->assertCount(1, $result);
        $this->assertSame($interval->getStart(), $result[0]->getStart());
        $this->assertSame($interval->getEnd(), $result[0]->getEnd());
    }

    public function testNonOverlappingIntervals()
    {
        $interval1 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 10:00:00'),
            new DateTimeImmutable('2023-01-01 11:00:00')
        );
        $interval2 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 12:00:00'),
            new DateTimeImmutable('2023-01-01 13:00:00')
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
            new DateTimeImmutable('2023-01-01 10:00:00'),
            new DateTimeImmutable('2023-01-01 12:00:00')
        );
        $interval2 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 10:30:00'),
            new DateTimeImmutable('2023-01-01 11:30:00')
        );

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals('2023-01-01 10:00:00', $result[0]->getStart()->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-01-01 12:00:00', $result[0]->getEnd()->format('Y-m-d H:i:s'));
    }

    public function testPartiallyOverlappingIntervals()
    {
        $interval1 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 10:00:00'),
            new DateTimeImmutable('2023-01-01 12:00:00')
        );
        $interval2 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 11:00:00'),
            new DateTimeImmutable('2023-01-01 13:00:00')
        );

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals('2023-01-01 10:00:00', $result[0]->getStart()->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-01-01 13:00:00', $result[0]->getEnd()->format('Y-m-d H:i:s'));
    }

    public function testAdjacentIntervals()
    {
        $interval1 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 10:00:00'),
            new DateTimeImmutable('2023-01-01 11:00:00')
        );
        $interval2 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 11:00:00'),
            new DateTimeImmutable('2023-01-01 12:00:00')
        );

        $result = TimeIntervalOperations::union([$interval1, $interval2]);

        $this->assertCount(1, $result);
        $this->assertEquals('2023-01-01 10:00:00', $result[0]->getStart()->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-01-01 12:00:00', $result[0]->getEnd()->format('Y-m-d H:i:s'));
    }

    public function testMultipleOverlappingAndNonOverlappingIntervals()
    {
        $interval1 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 10:00:00'),
            new DateTimeImmutable('2023-01-01 12:00:00')
        );
        $interval2 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 11:00:00'),
            new DateTimeImmutable('2023-01-01 13:00:00')
        );
        $interval3 = new TimeInterval(
            new DateTimeImmutable('2023-01-01 14:00:00'),
            new DateTimeImmutable('2023-01-01 15:00:00')
        );

        $result = TimeIntervalOperations::union([$interval1, $interval2, $interval3]);

        $this->assertCount(2, $result);
        $this->assertEquals('2023-01-01 10:00:00', $result[0]->getStart()->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-01-01 13:00:00', $result[0]->getEnd()->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-01-01 14:00:00', $result[1]->getStart()->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-01-01 15:00:00', $result[1]->getEnd()->format('Y-m-d H:i:s'));
    }
}