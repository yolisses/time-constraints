<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class TimeIntervalEqualityTest extends TestCase
{
    public function testEqualsReturnsTrueForIdenticalIntervals()
    {
        $interval1 = TimeInterval::fromStrings('2023-01-01 00:00:00', '2023-01-02 00:00:00', true, false);
        $interval2 = TimeInterval::fromStrings('2023-01-01 00:00:00', '2023-01-02 00:00:00', true, false);

        $this->assertEquals($interval1, $interval2);
    }

    public function testEqualsReturnsFalseForDifferentStartTimes()
    {
        $interval1 = TimeInterval::fromStrings('2023-01-01 00:00:00', '2023-01-02 00:00:00', true, false);
        $interval2 = TimeInterval::fromStrings('2023-01-01 01:00:00', '2023-01-02 00:00:00', true, false);

        $this->assertNotEquals($interval1, $interval2);
    }

    public function testEqualsReturnsFalseForDifferentEndTimes()
    {
        $interval1 = TimeInterval::fromStrings('2023-01-01 00:00:00', '2023-01-02 00:00:00', true, false);
        $interval2 = TimeInterval::fromStrings('2023-01-01 00:00:00', '2023-01-02 01:00:00', true, false);

        $this->assertNotEquals($interval1, $interval2);
    }

    public function testEqualsReturnsFalseForDifferentInclusivity()
    {
        $interval1 = TimeInterval::fromStrings('2023-01-01 00:00:00', '2023-01-02 00:00:00', true, false);
        $interval2 = TimeInterval::fromStrings('2023-01-01 00:00:00', '2023-01-02 00:00:00', false, false);

        $this->assertNotEquals($interval1, $interval2);
    }
}