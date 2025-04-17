<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\TimeInterval;
use Yolisses\TimeConstraints\Interval\TimeIntervalsUnion;

require_once __DIR__ . '/../utils/createTimeInterval.php';

class TimeIntervalsUnionTest extends TestCase
{

    /**
     * Example
     * ```php
     *  // 1 2 3
     *  // ●─●
     *  //   ○─●
     *  // ●───●
     *  $this->assertUnion(
     *      [[1, 2, true, true], [2, 3, false, true]],
     *      [[1, 3, true, true]]
     *  );
     * ```
     */
    private function assertUnion(array $input_intervals_data, array $expected_intervals_data)
    {
        $input_intervals = [];
        foreach ($input_intervals_data as $input_interval_data) {
            $input_intervals[] = createTimeInterval(
                $input_interval_data[0],
                $input_interval_data[1],
                $input_interval_data[2],
                $input_interval_data[3],
            );
        }

        $expected_intervals = [];
        foreach ($expected_intervals_data as $expected_interval_data) {
            $expected_intervals[] = createTimeInterval(
                $expected_interval_data[0],
                $expected_interval_data[1],
                $expected_interval_data[2],
                $expected_interval_data[3],
            );
        }

        $result = TimeIntervalsUnion::unionTimeIntervals($input_intervals);

        $this->assertEquals($expected_intervals, $result);
    }

    function testUnionTimeIntervalsEmpty()
    {
        // 1 2 3
        // 
        $this->assertUnion([], []);
    }

    function testUnionTimeIntervals1()
    {
        // 1 2 3
        // ●─●
        //   ●─●
        // ●───●
        $this->assertUnion(
            [
                [1, 2, true, true],
                [2, 3, true, true],
            ],
            [[1, 3, true, true]]
        );
    }

    function testUnionTimeIntervals2()
    {
        // 1 2 3
        // ●─●
        //   ○─●
        // ●───●
        $this->assertUnion(
            [
                [1, 2, true, true],
                [2, 3, false, true],
            ],
            [[1, 3, true, true]]
        );
    }

    function testUnionTimeIntervals3()
    {
        // 1 2 3
        // ●─○
        //   ●─●
        // ●───●
        $this->assertUnion(
            [
                [1, 2, true, false],
                [2, 3, true, true],
            ],
            [[1, 3, true, true]]
        );
    }

    function testUnionTimeIntervals4()
    {
        // 1 2 3
        // ●─○
        //   ○─●
        // ●─○─●
        $this->assertUnion(
            [
                [1, 2, true, false],
                [2, 3, false, true],
            ],
            [
                [1, 2, true, false],
                [2, 3, false, true],
            ]
        );
    }

    function testUnionTimeIntervalsWithEquality()
    {
        // 1 2 3
        // ●─●
        // ●─●
        // ●─●
        $this->assertUnion(
            [
                [1, 2, true, true],
                [1, 2, true, true],
            ],
            [[1, 2, true, true]]
        );
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