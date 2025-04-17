<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\Edge;
use Yolisses\TimeConstraints\Interval\TimeIntervalsUnion;

require_once __DIR__ . '/../utils/createEdge.php';
require_once __DIR__ . '/../utils/createTimeInterval.php';

class TimeIntervalsUnionEdgeSortTest extends TestCase
{
    function createIntervalsFromArrays(array $intervals_data)
    {
        $intervals = [];
        foreach ($intervals_data as $interval_data) {
            $intervals[] = createTimeInterval(
                $interval_data[0],
                $interval_data[1],
                $interval_data[2],
                $interval_data[3],
            );
        }
        return $intervals;
    }

    public function testSort1()
    {
        // 1 2 3 4 5 6 7 8 9
        // ●───●

        $intervals = [
            createTimeInterval(1, 3, true, true),
        ];

        $edges = Edge::getTimeIntervalsEdges($intervals);
        TimeIntervalsUnion::sortEdges($edges);

        $this->assertEquals(
            [
                createEdge(1, true, true),
                createEdge(3, false, true),
            ],
            $edges
        );
    }

    public function testSort2()
    {
        // 1 2 3 4 5 6 7 8 9
        // ●───●
        //   ●───●

        $intervals = [
            createTimeInterval(1, 3, true, true),
            createTimeInterval(2, 4, true, true),
        ];

        $edges = Edge::getTimeIntervalsEdges($intervals);
        TimeIntervalsUnion::sortEdges($edges);

        $this->assertEquals(
            [
                createEdge(1, true, true),
                createEdge(2, true, true),
                createEdge(3, false, true),
                createEdge(4, false, true),
            ],
            $edges
        );
    }

    public function testSort3()
    {
        // 1 2 3 4 5 6 7 8 9
        // ●───●
        // ○───○
        // ●───●
        // ○───○

        $intervals = [
            createTimeInterval(1, 3, true, true),
            createTimeInterval(1, 3, false, false),
            createTimeInterval(1, 3, true, true),
            createTimeInterval(1, 3, false, false),
        ];

        $edges = Edge::getTimeIntervalsEdges($intervals);
        TimeIntervalsUnion::sortEdges($edges);

        var_dump($edges);

        $this->assertEquals(
            [
                createEdge(1, true, false),
                createEdge(1, true, false),
                createEdge(1, true, true),
                createEdge(1, true, true),
                createEdge(3, false, false),
                createEdge(3, false, false),
                createEdge(3, false, true),
                createEdge(3, false, true),
            ],
            $edges
        );
    }

    public function testSort4()
    {
        // 1 2 3 4 5 6 7 8 9
        //     ●─────●
        //         ○───●
        //     ○─────────○
        //   ○───●
        //         ●───○
        // ●───●
        //         ●───●

        $intervals = [
            createTimeInterval(3, 6, true, true),
            createTimeInterval(5, 7, false, true),
            createTimeInterval(3, 8, false, false),
            createTimeInterval(2, 4, false, true),
            createTimeInterval(5, 7, true, false),
            createTimeInterval(1, 3, true, true),
            createTimeInterval(5, 7, true, true),
        ];

        $edges = Edge::getTimeIntervalsEdges($intervals);
        TimeIntervalsUnion::sortEdges($edges);

        $this->assertEquals(
            [
                createEdge(1, true, true),
                createEdge(2, true, false),
                createEdge(3, true, false),
                createEdge(3, true, true),
                createEdge(3, false, true),
                createEdge(4, false, true),
                createEdge(5, true, false),
                createEdge(5, true, true),
                createEdge(5, true, true),
                createEdge(6, false, true),
                createEdge(7, false, false),
                createEdge(7, false, true),
                createEdge(7, false, true),
                createEdge(8, false, false),
            ],
            $edges
        );
    }
}