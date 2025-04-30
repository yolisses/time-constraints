<?php

namespace Yolisses\TimeConstraints\Interval\Tests;

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TimeIntervalIntersection;

require_once __DIR__ . '/../utils/createTimeInterval.php';

class TimeIntervalIntersectionTest extends TestCase
{
    public function testEmptyArrayReturnsEmptyArray(): void
    {
        $result = TimeIntervalIntersection::intersection([], []);
        $this->assertEmpty($result);

        $interval = createTimeInterval(1, 2, true, true);
        $result = TimeIntervalIntersection::intersection([$interval], []);
        $this->assertEmpty($result);

        $result = TimeIntervalIntersection::intersection([], [$interval]);
        $this->assertEmpty($result);
    }

    public function testNonOverlappingIntervals(): void
    {
        $intervals1 = [createTimeInterval(1, 2, true, true)];
        $intervals2 = [createTimeInterval(3, 4, true, true)];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);
        $this->assertEmpty($result);
    }

    public function testSingleOverlappingIntervals(): void
    {
        $intervals1 = [createTimeInterval(1, 3, true, true)];
        $intervals2 = [createTimeInterval(2, 4, true, true)];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);

        $this->assertEquals([
            createTimeInterval(2, 3, true, true),
        ], $result);
    }

    public function testAdjacentIntervalsWithIncludedEndpoints(): void
    {
        $intervals1 = [createTimeInterval(1, 2, true, true)];
        $intervals2 = [createTimeInterval(2, 3, true, true)];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);

        $this->assertEquals([
            createTimeInterval(2, 2, true, true),
        ], $result);
    }

    public function testAdjacentIntervalsWithExcludedEndpoints(): void
    {
        $intervals1 = [createTimeInterval(1, 2, true, false)];
        $intervals2 = [createTimeInterval(2, 3, false, true)];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);
        $this->assertEmpty($result);
    }

    public function testIntervalsWithMixedEndpointInclusion(): void
    {
        $intervals1 = [createTimeInterval(1, 3, true, false)];
        $intervals2 = [createTimeInterval(2, 4, false, true)];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);

        $this->assertEquals([
            createTimeInterval(2, 3, false, false),
        ], $result);
    }

    public function testMultipleOverlappingIntervals(): void
    {
        $intervals1 = [
            createTimeInterval(1, 3, true, true),
            createTimeInterval(5, 7, true, true),
        ];
        $intervals2 = [
            createTimeInterval(2, 4, true, true),
            createTimeInterval(6, 8, true, true),
        ];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);

        $this->assertEquals([
            createTimeInterval(2, 3, true, true),
            createTimeInterval(6, 7, true, true),
        ], $result);
    }

    public function testUnsortedInputIntervals(): void
    {
        $intervals1 = [
            createTimeInterval(3, 4, true, true),
            createTimeInterval(1, 2, true, true),
        ];
        $intervals2 = [
            createTimeInterval(1, 3, true, true),
        ];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);

        $this->assertEquals([
            createTimeInterval(1, 2, true, true),
            createTimeInterval(3, 3, true, true),
        ], $result);
    }

    public function testEqualStartTimesWithDifferentStartInclusion(): void
    {
        $intervals1 = [createTimeInterval(1, 3, false, true)];
        $intervals2 = [createTimeInterval(1, 2, true, true)];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);

        $this->assertEquals([
            createTimeInterval(1, 2, false, true),
        ], $result);
    }

    public function testEqualEndTimesWithDifferentEndInclusion(): void
    {
        $intervals1 = [createTimeInterval(1, 3, true, false)];
        $intervals2 = [createTimeInterval(2, 3, true, true)];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);

        $this->assertEquals([
            createTimeInterval(2, 3, true, false),
        ], $result);
    }

    public function testCompletelyContainedInterval(): void
    {
        $intervals1 = [createTimeInterval(1, 4, true, true)];
        $intervals2 = [createTimeInterval(2, 3, true, true)];

        $result = TimeIntervalIntersection::intersection($intervals1, $intervals2);

        $this->assertEquals([
            createTimeInterval(2, 3, true, true),
        ], $result);
    }
}