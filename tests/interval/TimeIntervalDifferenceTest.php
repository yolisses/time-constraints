<?php

namespace Yolisses\TimeConstraints\Interval\Tests;

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TimeIntervalDifference;

require_once __DIR__ . '/../utils/createTimeInterval.php';

class TimeIntervalDifferenceTest extends TestCase
{
    public function testEmptyFirstArrayReturnsEmptyArray(): void
    {
        $result = TimeIntervalDifference::difference([], [createTimeInterval(1, 2, true, true)]);
        $this->assertEmpty($result);
    }

    public function testEmptySecondArrayReturnsFirstArray(): void
    {
        $interval = createTimeInterval(1, 2, true, true);
        $result = TimeIntervalDifference::difference([$interval], []);

        $this->assertEquals([$interval], $result);
    }

    public function testNonOverlappingIntervalsReturnsFirstArray(): void
    {
        $interval1 = createTimeInterval(1, 2, true, true);
        $interval2 = createTimeInterval(3, 4, true, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEquals([$interval1], $result);
    }

    public function testCompletelyOverlappingIntervalReturnsEmpty(): void
    {
        $interval1 = createTimeInterval(2, 3, true, true);
        $interval2 = createTimeInterval(1, 4, true, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEmpty($result);
    }

    public function testPartialOverlapAtStart(): void
    {
        $interval1 = createTimeInterval(1, 3, true, true);
        $interval2 = createTimeInterval(1, 2, true, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEquals([
            createTimeInterval(2, 3, false, true),
        ], $result);
    }

    public function testPartialOverlapAtEnd(): void
    {
        $interval1 = createTimeInterval(1, 3, true, true);
        $interval2 = createTimeInterval(2, 3, true, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEquals([
            createTimeInterval(1, 2, true, false),
        ], $result);
    }

    public function testIntervalContainedWithinGap(): void
    {
        $interval1 = createTimeInterval(1, 4, true, true);
        $interval2 = createTimeInterval(2, 3, true, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEquals([
            createTimeInterval(1, 2, true, false),
            createTimeInterval(3, 4, false, true),
        ], $result);
    }

    public function testAdjacentIntervalsWithIncludedEndpoints(): void
    {
        $interval1 = createTimeInterval(1, 2, true, true);
        $interval2 = createTimeInterval(2, 3, true, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEquals([
            createTimeInterval(1, 2, true, false),
        ], $result);
    }

    public function testAdjacentIntervalsWithExcludedEndpoints(): void
    {
        $interval1 = createTimeInterval(1, 2, true, false);
        $interval2 = createTimeInterval(2, 3, false, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEquals([
            createTimeInterval(1, 2, true, false),
        ], $result);
    }

    public function testMixedEndpointInclusion(): void
    {
        $interval1 = createTimeInterval(1, 3, true, false);
        $interval2 = createTimeInterval(2, 3, true, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEquals([
            createTimeInterval(1, 2, true, false),
        ], $result);
    }

    public function testMultipleOverlappingIntervals(): void
    {
        $intervals1 = [
            createTimeInterval(1, 4, true, true),
            createTimeInterval(5, 7, true, true),
        ];
        $intervals2 = [
            createTimeInterval(2, 3, true, true),
            createTimeInterval(6, 8, true, true),
        ];

        $result = TimeIntervalDifference::difference($intervals1, $intervals2);

        $this->assertEquals([
            createTimeInterval(1, 2, true, false),
            createTimeInterval(3, 4, false, true),
            createTimeInterval(5, 6, true, false),
        ], $result);
    }

    public function testEqualStartTimesWithDifferentInclusion(): void
    {
        $interval1 = createTimeInterval(1, 3, true, true);
        $interval2 = createTimeInterval(1, 2, false, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEquals([
            createTimeInterval(1, 1, true, true),
            createTimeInterval(2, 3, false, true),
        ], $result);
    }

    public function testSinglePointInterval(): void
    {
        $interval1 = createTimeInterval(1, 1, true, true);
        $interval2 = createTimeInterval(1, 2, true, true);

        $result = TimeIntervalDifference::difference([$interval1], [$interval2]);

        $this->assertEmpty($result);
    }
}