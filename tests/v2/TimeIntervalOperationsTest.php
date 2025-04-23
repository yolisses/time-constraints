<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\V2\TimeInterval;
use Yolisses\TimeConstraints\V2\TimeIntervalOperations;

class TimeIntervalOperationsTest extends TestCase
{
    private function createDateTime(string $date): DateTimeImmutable
    {
        return new DateTimeImmutable($date);
    }

    public function testUnionEmptyArray()
    {
        $result = TimeIntervalOperations::union([]);
        $this->assertEmpty($result);
    }

    public function testUnionSingleInterval()
    {
        $interval = new TimeInterval(
            $this->createDateTime('2023-01-01'),
            $this->createDateTime('2023-01-02')
        );
        $result = TimeIntervalOperations::union([$interval]);
        $this->assertCount(1, $result);
        $this->assertEquals($interval->start, $result[0]->start);
        $this->assertEquals($interval->end, $result[0]->end);
        $this->assertTrue($result[0]->start_is_included);
        $this->assertTrue($result[0]->end_is_included);
    }

    public function testUnionOverlappingIntervals()
    {
        $intervals = [
            new TimeInterval(
                $this->createDateTime('2023-01-01'),
                $this->createDateTime('2023-01-03')
            ),
            new TimeInterval(
                $this->createDateTime('2023-01-02'),
                $this->createDateTime('2023-01-04')
            )
        ];
        $result = TimeIntervalOperations::union($intervals);
        $this->assertCount(1, $result);
        $this->assertEquals($this->createDateTime('2023-01-01'), $result[0]->start);
        $this->assertEquals($this->createDateTime('2023-01-04'), $result[0]->end);
    }

    public function testUnionNonOverlappingIntervals()
    {
        $intervals = [
            new TimeInterval(
                $this->createDateTime('2023-01-01'),
                $this->createDateTime('2023-01-02')
            ),
            new TimeInterval(
                $this->createDateTime('2023-01-03'),
                $this->createDateTime('2023-01-04')
            )
        ];
        $result = TimeIntervalOperations::union($intervals);
        $this->assertCount(2, $result);
        $this->assertEquals($this->createDateTime('2023-01-01'), $result[0]->start);
        $this->assertEquals($this->createDateTime('2023-01-02'), $result[0]->end);
        $this->assertEquals($this->createDateTime('2023-01-03'), $result[1]->start);
        $this->assertEquals($this->createDateTime('2023-01-04'), $result[1]->end);
    }

    public function testIntersectionEmptyArrays()
    {
        $result = TimeIntervalOperations::intersection([], []);
        $this->assertEmpty($result);
    }

    public function testIntersectionNoOverlap()
    {
        $intervals1 = [
            new TimeInterval(
                $this->createDateTime('2023-01-01'),
                $this->createDateTime('2023-01-02')
            )
        ];
        $intervals2 = [
            new TimeInterval(
                $this->createDateTime('2023-01-03'),
                $this->createDateTime('2023-01-04')
            )
        ];
        $result = TimeIntervalOperations::intersection($intervals1, $intervals2);
        $this->assertEmpty($result);
    }

    public function testIntersectionOverlapping()
    {
        $intervals1 = [
            new TimeInterval(
                $this->createDateTime('2023-01-01'),
                $this->createDateTime('2023-01-03')
            )
        ];
        $intervals2 = [
            new TimeInterval(
                $this->createDateTime('2023-01-02'),
                $this->createDateTime('2023-01-04')
            )
        ];
        $result = TimeIntervalOperations::intersection($intervals1, $intervals2);
        $this->assertCount(1, $result);
        $this->assertEquals($this->createDateTime('2023-01-02'), $result[0]->start);
        $this->assertEquals($this->createDateTime('2023-01-03'), $result[0]->end);
        $this->assertTrue($result[0]->start_is_included);
        $this->assertTrue($result[0]->end_is_included);
    }

    public function testIntersectionPointIntersection()
    {
        $intervals1 = [
            new TimeInterval(
                $this->createDateTime('2023-01-01'),
                $this->createDateTime('2023-01-02'),
                true,
                true
            )
        ];
        $intervals2 = [
            new TimeInterval(
                $this->createDateTime('2023-01-02'),
                $this->createDateTime('2023-01-03'),
                true,
                true
            )
        ];
        $result = TimeIntervalOperations::intersection($intervals1, $intervals2);
        $this->assertCount(1, $result);
        $this->assertEquals($this->createDateTime('2023-01-02'), $result[0]->start);
        $this->assertEquals($this->createDateTime('2023-01-02'), $result[0]->end);
        $this->assertTrue($result[0]->start_is_included);
        $this->assertTrue($result[0]->end_is_included);
    }

    public function testDifferenceEmptyArrays()
    {
        $result = TimeIntervalOperations::difference([], []);
        $this->assertEmpty($result);
    }

    public function testDifferenceNoOverlap()
    {
        $intervals1 = [
            new TimeInterval(
                $this->createDateTime('2023-01-01'),
                $this->createDateTime('2023-01-02')
            )
        ];
        $intervals2 = [
            new TimeInterval(
                $this->createDateTime('2023-01-03'),
                $this->createDateTime('2023-01-04')
            )
        ];
        $result = TimeIntervalOperations::difference($intervals1, $intervals2);
        $this->assertCount(1, $result);
        $this->assertEquals($this->createDateTime('2023-01-01'), $result[0]->start);
        $this->assertEquals($this->createDateTime('2023-01-02'), $result[0]->end);
    }

    public function testDifferenceCompleteOverlap()
    {
        $intervals1 = [
            new TimeInterval(
                $this->createDateTime('2023-01-02'),
                $this->createDateTime('2023-01-03')
            )
        ];
        $intervals2 = [
            new TimeInterval(
                $this->createDateTime('2023-01-01'),
                $this->createDateTime('2023-01-04')
            )
        ];
        $result = TimeIntervalOperations::difference($intervals1, $intervals2);
        $this->assertEmpty($result);
    }

    public function testDifferencePartialOverlap()
    {
        $intervals1 = [
            new TimeInterval(
                $this->createDateTime('2023-01-01'),
                $this->createDateTime('2023-01-04')
            )
        ];
        $intervals2 = [
            new TimeInterval(
                $this->createDateTime('2023-01-02'),
                $this->createDateTime('2023-01-03')
            )
        ];
        $result = TimeIntervalOperations::difference($intervals1, $intervals2);
        $this->assertCount(2, $result);
        $this->assertEquals($this->createDateTime('2023-01-01'), $result[0]->start);
        $this->assertEquals($this->createDateTime('2023-01-02'), $result[0]->end);
        $this->assertEquals($this->createDateTime('2023-01-03'), $result[1]->start);
        $this->assertEquals($this->createDateTime('2023-01-04'), $result[1]->end);
    }

    public function testDifferenceWithExclusion()
    {
        $intervals1 = [
            new TimeInterval(
                $this->createDateTime('2023-01-01'),
                $this->createDateTime('2023-01-04'),
                true,
                false
            )
        ];
        $intervals2 = [
            new TimeInterval(
                $this->createDateTime('2023-01-02'),
                $this->createDateTime('2023-01-03'),
                true,
                true
            )
        ];
        $result = TimeIntervalOperations::difference($intervals1, $intervals2);
        $this->assertCount(2, $result);
        $this->assertEquals($this->createDateTime('2023-01-01'), $result[0]->start);
        $this->assertEquals($this->createDateTime('2023-01-02'), $result[0]->end);
        $this->assertTrue($result[0]->start_is_included);
        $this->assertFalse($result[0]->end_is_included);
        $this->assertEquals($this->createDateTime('2023-01-03'), $result[1]->start);
        $this->assertEquals($this->createDateTime('2023-01-04'), $result[1]->end);
        $this->assertFalse($result[1]->start_is_included);
        $this->assertFalse($result[1]->end_is_included);
    }
}

?>