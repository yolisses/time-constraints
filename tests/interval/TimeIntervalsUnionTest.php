<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TimeInterval;
use Yolisses\TimeConstraints\Interval\TimeIntervalsUnion;

require_once __DIR__ . '/../utils/createTimeInterval.php';

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

    function testUnionTimeIntervals1()
    {
        // 1 2 3 4 5 6 7
        // ●─●
        //   ●─●
        // ●───●

        $intervals = [
            createTimeInterval(1, 2, true, true),
            createTimeInterval(2, 3, true, true),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                createTimeInterval(1, 3, true, true),
            ],
            $result
        );
    }

    function testUnionTimeIntervals2()
    {
        // 1 2 3 4 5 6 7
        // ●─●
        //   ○─●
        // ●───●

        $intervals = [
            createTimeInterval(1, 2, true, true),
            createTimeInterval(2, 3, false, true),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                createTimeInterval(1, 3, true, true),
            ],
            $result
        );
    }

    function testUnionTimeIntervals3()
    {
        // 1 2 3 4 5 6 7
        // ●─○
        //   ●─●
        // ●───●

        $intervals = [
            createTimeInterval(1, 2, true, false),
            createTimeInterval(2, 3, true, true),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                createTimeInterval(1, 3, true, true),
            ],
            $result
        );
    }

    function testUnionTimeIntervals4()
    {
        // 1 2 3 4 5 6 7
        // ●─○
        //   ○─●
        // ●─○─●

        $intervals = [
            createTimeInterval(1, 2, true, false),
            createTimeInterval(2, 3, false, true),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                createTimeInterval(1, 2, true, false),
                createTimeInterval(2, 3, false, true),
            ],
            $result
        );
    }

    function testUnionTimeIntervalsWithEquality()
    {
        // 1 2 3 4 5 6 7
        // ●───●
        // ●───●
        // ●───●

        $intervals = [
            createTimeInterval(1, 4, true, true),
            createTimeInterval(1, 4, true, true),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals([
            createTimeInterval(1, 4, true, true),
        ], $result);
    }

    function testUnionTimeIntervalsWithTime()
    {
        // 0 1 2 3 4
        // ●─●
        //   ●─────●
        // ●───────●

        $intervals = [
            new TimeInterval(
                new DateTimeImmutable('2021-01-01 00:00:00'),
                new DateTimeImmutable('2021-01-01 00:02:00'),
                true,
                true,
            ),
            new TimeInterval(
                new DateTimeImmutable('2021-01-01 00:01:00'),
                new DateTimeImmutable('2021-01-01 00:04:00'),
                true,
                true,
            ),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals([
            new TimeInterval(
                new DateTimeImmutable('2021-01-01 00:00:00'),
                new DateTimeImmutable('2021-01-01 00:04:00'),
                true,
                true,
            ),
        ], $result);
    }

    function testUnionTimeIntervalsSimple()
    {
        // 1 2 3 4 5 6 7
        // ●─●
        //   ●─●
        //       ●─●
        // ●───● ●─●

        $intervals = [
            createTimeInterval(1, 2, true, true),
            createTimeInterval(2, 3, true, true),
            createTimeInterval(4, 5, true, true),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                createTimeInterval(1, 3, true, true),
                createTimeInterval(4, 5, true, true),
            ],
            $result
        );
    }

    function testUnionTimeIntervalsComplex()
    {
        // 1 2 3 4 5 6 7
        // ●─●
        //       ●─────●
        //   ●─●
        //         ●─●
        // ●───● ●─────●
        $intervals = [
            createTimeInterval(1, 2, true, true),
            createTimeInterval(4, 7, true, true),
            createTimeInterval(2, 3, true, true),
            createTimeInterval(5, 6, true, true),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                createTimeInterval(1, 3, true, true),
                createTimeInterval(4, 7, true, true),
            ],
            $result
        );
    }
}