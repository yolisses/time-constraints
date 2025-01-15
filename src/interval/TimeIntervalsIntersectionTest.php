<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TestUtil;
use Yolisses\TimeConstraints\Interval\TimeIntervalsIntersection;

class TimeIntervalsIntersectionTest extends TestCase
{
    function testIntersectionTimeIntervalsEmpty()
    {
        //1 2 3 4 5 6 7
        //

        $intervals_1 = [];
        $intervals_2 = [];

        $result = TimeIntervalsIntersection::intersectionTimeIntervals($intervals_1, $intervals_2);

        $this->assertEquals([], $result);
    }

    function testIntersectionTimeIntervalsWithEquality()
    {
        // 1 2 3 4 5 6 7
        // ██████
        // ██████

        $intervals_1 = [
            TestUtil::createTimeInterval(1, 4),
        ];
        $intervals_2 = [
            TestUtil::createTimeInterval(1, 4),
        ];

        $result = TimeIntervalsIntersection::intersectionTimeIntervals($intervals_1, $intervals_2);

        $this->assertEquals(
            [
                TestUtil::createTimeInterval(1, 4),
            ],
            $result
        );
    }

    function testIntersectionTimeIntervalsSimple()
    {
        // 1 2 3 4 5 6 7
        // ██████
        //   ██    ██
        //   ██

        $intervals_1 = [
            TestUtil::createTimeInterval(1, 4),
        ];
        $intervals_2 = [
            TestUtil::createTimeInterval(2, 3),
            TestUtil::createTimeInterval(5, 6),
        ];

        $result = TimeIntervalsIntersection::intersectionTimeIntervals($intervals_1, $intervals_2);

        $this->assertEquals(
            [
                TestUtil::createTimeInterval(2, 3),
            ],
            $result
        );
    }

    function testIntersectionTimeIntervalsComplex()
    {
        //                     11  13
        // 1 2 3 4 5 6 7 8 9 10  12
        // ██  ██████  ██  ██  ████
        //   ██    ██      ████████
        //         ██      ██  ████

        $intervals_1 = [
            TestUtil::createTimeInterval(1, 2),
            TestUtil::createTimeInterval(3, 6),
            TestUtil::createTimeInterval(7, 8),
            TestUtil::createTimeInterval(9, 10),
            TestUtil::createTimeInterval(11, 13),
        ];
        $intervals_2 = [
            TestUtil::createTimeInterval(2, 3),
            TestUtil::createTimeInterval(5, 6),
            TestUtil::createTimeInterval(9, 13),
        ];

        $result = TimeIntervalsIntersection::intersectionTimeIntervals($intervals_1, $intervals_2);

        $this->assertEquals(
            [
                TestUtil::createTimeInterval(5, 6),
                TestUtil::createTimeInterval(9, 10),
                TestUtil::createTimeInterval(11, 13),
            ],
            $result
        );
    }
}