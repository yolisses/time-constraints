<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TimeInterval;
use Yolisses\TimeConstraints\Interval\TimeIntervalsIntersection;

require_once __DIR__ . '/createTimeInterval.php';

class TimeIntervalsIntersectionTest extends TestCase
{
    static function createTimeInterval(int $start, int $end)
    {
        return new TimeInterval(
            new \DateTimeImmutable("0001-01-$start"),
            new \DateTimeImmutable("0001-01-$end")
        );
    }

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
            createTimeInterval(1, 4),
        ];
        $intervals_2 = [
            createTimeInterval(1, 4),
        ];

        $result = TimeIntervalsIntersection::intersectionTimeIntervals($intervals_1, $intervals_2);

        $this->assertEquals(
            [
                createTimeInterval(1, 4),
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
            createTimeInterval(1, 4),
        ];
        $intervals_2 = [
            createTimeInterval(2, 3),
            createTimeInterval(5, 6),
        ];

        $result = TimeIntervalsIntersection::intersectionTimeIntervals($intervals_1, $intervals_2);

        $this->assertEquals(
            [
                createTimeInterval(2, 3),
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
            createTimeInterval(1, 2),
            createTimeInterval(3, 6),
            createTimeInterval(7, 8),
            createTimeInterval(9, 10),
            createTimeInterval(11, 13),
        ];
        $intervals_2 = [
            createTimeInterval(2, 3),
            createTimeInterval(5, 6),
            createTimeInterval(9, 13),
        ];

        $result = TimeIntervalsIntersection::intersectionTimeIntervals($intervals_1, $intervals_2);

        $this->assertEquals(
            [
                createTimeInterval(5, 6),
                createTimeInterval(9, 10),
                createTimeInterval(11, 13),
            ],
            $result
        );
    }
}