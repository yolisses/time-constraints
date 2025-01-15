<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TestUtil;
use Yolisses\TimeConstraints\Interval\TimeIntervalsUnion;

class TimeIntervalsUnionTest extends TestCase
{
    function testUnionTimeIntervalsEmpty()
    {
        //1 2 3 4 5 6 7
        //

        $intervals = [
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals([], $result);
    }

    function testUnionTimeIntervalsWithEquality()
    {
        // 1 2 3 4 5 6 7
        // ██████
        // ██████

        $intervals = [
            TestUtil::createTimeInterval(1, 4),
            TestUtil::createTimeInterval(1, 4),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals([
            TestUtil::createTimeInterval(1, 4),
        ], $result);
    }

    function testUnionTimeIntervalsSimple()
    {
        // 1 2 3 4 5 6 7
        // ██
        //   ██
        //       ██
        // ████  ██

        $intervals = [
            TestUtil::createTimeInterval(1, 2),
            TestUtil::createTimeInterval(2, 3),
            TestUtil::createTimeInterval(4, 5),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                TestUtil::createTimeInterval(1, 3),
                TestUtil::createTimeInterval(4, 5),
            ],
            $result
        );
    }

    function testUnionTimeIntervalsComplex()
    {
        // 1 2 3 4 5 6 7
        // ██
        //       ██████
        //   ██
        //         ██
        // ████  ██████
        $intervals = [
            TestUtil::createTimeInterval(1, 2),
            TestUtil::createTimeInterval(4, 7),
            TestUtil::createTimeInterval(2, 3),
            TestUtil::createTimeInterval(5, 6),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                TestUtil::createTimeInterval(1, 3),
                TestUtil::createTimeInterval(4, 7),
            ],
            $result
        );
    }
}