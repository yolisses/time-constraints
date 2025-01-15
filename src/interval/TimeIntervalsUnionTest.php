<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TestUtil;
use Yolisses\TimeConstraints\Interval\TimeIntervalsUnion;

class TimeIntervalsUnionTest extends TestCase
{
    function testUnionTimeIntervalsEmpty()
    {
        //0 1 2 3 4 5 6 7
        //

        $intervals = [
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals([], $result);
    }

    function testUnionTimeIntervalsWithEquality()
    {
        //0 1 2 3 4 5 6 7
        //  ██████
        //  ██████

        $intervals = [
            TestUtil::createSimpleTimeInterval(1, 4),
            TestUtil::createSimpleTimeInterval(1, 4),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals([
            TestUtil::createSimpleTimeInterval(1, 4),
        ], $result);
    }

    function testUnionTimeIntervalsSimple()
    {
        //0 1 2 3 4 5 6 7
        //  ██
        //    ██
        //        ██
        //  ████  ██

        $intervals = [
            TestUtil::createSimpleTimeInterval(1, 2),
            TestUtil::createSimpleTimeInterval(2, 3),
            TestUtil::createSimpleTimeInterval(4, 5),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                TestUtil::createSimpleTimeInterval(1, 3),
                TestUtil::createSimpleTimeInterval(4, 5),
            ],
            $result
        );
    }

    function testUnionTimeIntervalsComplex()
    {
        //0 1 2 3 4 5 6 7
        //  ██
        //        ██████
        //    ██
        //          ██
        //  ████  ██████
        $intervals = [
            TestUtil::createSimpleTimeInterval(1, 2),
            TestUtil::createSimpleTimeInterval(4, 7),
            TestUtil::createSimpleTimeInterval(2, 3),
            TestUtil::createSimpleTimeInterval(5, 6),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                TestUtil::createSimpleTimeInterval(1, 3),
                TestUtil::createSimpleTimeInterval(4, 7),
            ],
            $result
        );
    }
}