<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TestUtil;
use Yolisses\TimeConstraints\Interval\UnionSimpleTimeIntervals;

class UnionSimpleIntervalsTest extends TestCase
{
    function testUnionSimpleIntervalsSimple()
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

        $result = UnionSimpleTimeIntervals::unionSimpleTimeIntervals($intervals);

        $this->assertEquals(
            [
                TestUtil::createSimpleTimeInterval(1, 3),
                TestUtil::createSimpleTimeInterval(4, 5),
            ],
            $result
        );
    }

    function testUnionSimpleIntervalsComplex()
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

        $result = UnionSimpleTimeIntervals::unionSimpleTimeIntervals($intervals);

        $this->assertEquals(
            [
                TestUtil::createSimpleTimeInterval(1, 3),
                TestUtil::createSimpleTimeInterval(4, 7),
            ],
            $result
        );
    }
}