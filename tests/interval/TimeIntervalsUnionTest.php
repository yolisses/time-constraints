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

    function testUnionTimeIntervalsWithEquality()
    {
        // 1 2 3 4 5 6 7
        // ██████
        // ██████
        // ██████

        $intervals = [
            createTimeInterval(1, 4),
            createTimeInterval(1, 4),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals([
            createTimeInterval(1, 4),
        ], $result);
    }

    function testUnionTimeIntervalsWithTime()
    {
        // 0 1 2 3 4
        // ████
        //   ██████
        // ████████

        $intervals = [
            new TimeInterval(
                new DateTimeImmutable('2021-01-01 00:00:00'),
                new DateTimeImmutable('2021-01-01 00:02:00')
            ),
            new TimeInterval(
                new DateTimeImmutable('2021-01-01 00:01:00'),
                new DateTimeImmutable('2021-01-01 00:04:00')
            ),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals([
            new TimeInterval(
                new DateTimeImmutable('2021-01-01 00:00:00'),
                new DateTimeImmutable('2021-01-01 00:04:00')
            ),
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
            createTimeInterval(1, 2),
            createTimeInterval(2, 3),
            createTimeInterval(4, 5),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                createTimeInterval(1, 3),
                createTimeInterval(4, 5),
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
            createTimeInterval(1, 2),
            createTimeInterval(4, 7),
            createTimeInterval(2, 3),
            createTimeInterval(5, 6),
        ];

        $result = TimeIntervalsUnion::unionTimeIntervals($intervals);

        $this->assertEquals(
            [
                createTimeInterval(1, 3),
                createTimeInterval(4, 7),
            ],
            $result
        );
    }
}