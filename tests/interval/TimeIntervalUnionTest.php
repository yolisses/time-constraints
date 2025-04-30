<?php

namespace Yolisses\TimeConstraints\Interval\Tests;

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TimeIntervalUnion;

require_once __DIR__ . '/../utils/createTimeInterval.php';

class TimeIntervalUnionTest extends TestCase
{
    public function testEmptyArrayReturnsEmptyArray(): void
    {
        $result = TimeIntervalUnion::union([]);
        $this->assertEmpty($result);
    }

    public function testSingleIntervalReturnsSameInterval(): void
    {
        $interval = createTimeInterval(1, 2, true, true);
        $result = TimeIntervalUnion::union([$interval]);

        $this->assertEquals([
            createTimeInterval(1, 2, true, true),
        ], $result);
    }

    public function testNonOverlappingIntervals(): void
    {
        $interval1 = createTimeInterval(1, 2, true, true);
        $interval2 = createTimeInterval(3, 4, true, true);

        $result = TimeIntervalUnion::union([$interval1, $interval2]);

        $this->assertEquals([
            createTimeInterval(1, 2, true, true),
            createTimeInterval(3, 4, true, true),
        ], $result);
    }

    public function testOverlappingIntervals(): void
    {
        $interval1 = createTimeInterval(1, 3, true, true);
        $interval2 = createTimeInterval(2, 4, true, true);

        $result = TimeIntervalUnion::union([$interval1, $interval2]);

        $this->assertEquals([
            createTimeInterval(1, 4, true, true),
        ], $result);
    }

    public function testAdjacentIntervalsWithIncludedEndpoints(): void
    {
        $interval1 = createTimeInterval(1, 2, true, true);
        $interval2 = createTimeInterval(2, 3, true, true);

        $result = TimeIntervalUnion::union([$interval1, $interval2]);

        $this->assertEquals([
            createTimeInterval(1, 3, true, true),
        ], $result);
    }

    public function testAdjacentIntervalsWithExcludedEndpoints(): void
    {
        $interval1 = createTimeInterval(1, 2, true, false);
        $interval2 = createTimeInterval(2, 3, false, true);

        $result = TimeIntervalUnion::union([$interval1, $interval2]);

        $this->assertEquals([
            createTimeInterval(1, 2, true, false),
            createTimeInterval(2, 3, false, true),
        ], $result);
    }

    public function testMultipleOverlappingAndNonOverlappingIntervals(): void
    {
        $intervals = [
            createTimeInterval(1, 2, true, true),
            createTimeInterval(1, 3, true, true),
            createTimeInterval(4, 5, true, true),
            createTimeInterval(4, 6, true, true),
        ];

        $result = TimeIntervalUnion::union($intervals);

        $this->assertEquals([
            createTimeInterval(1, 3, true, true),
            createTimeInterval(4, 6, true, true),
        ], $result);
    }

    public function testIntervalsWithMixedEndpointInclusion(): void
    {
        $interval1 = createTimeInterval(1, 2, true, false);
        $interval2 = createTimeInterval(2, 3, true, true);

        $result = TimeIntervalUnion::union([$interval1, $interval2]);

        $this->assertEquals([
            createTimeInterval(1, 3, true, true),
        ], $result);
    }

    public function testUnsortedInputIntervals(): void
    {
        $intervals = [
            createTimeInterval(3, 4, true, true),
            createTimeInterval(1, 2, true, true),
        ];

        $result = TimeIntervalUnion::union($intervals);

        $this->assertEquals([
            createTimeInterval(1, 2, true, true),
            createTimeInterval(3, 4, true, true),
        ], $result);
    }

    public function testEqualStartTimesWithDifferentStartInclusion(): void
    {
        $interval1 = createTimeInterval(1, 2, false, true);
        $interval2 = createTimeInterval(1, 3, true, false);

        $result = TimeIntervalUnion::union([$interval1, $interval2]);

        $this->assertEquals([
            createTimeInterval(1, 3, true, false),
        ], $result);
    }
}