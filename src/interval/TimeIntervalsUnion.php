<?php

namespace Yolisses\TimeConstraints\Interval;

class TimeIntervalsUnion
{
    /**
     * @param array<TimeInterval> $time_intervals
     * @return array<TimeInterval>
     */
    static function unionTimeIntervals(array $time_intervals)
    {
        $edges = Edge::getTimeIntervalsEdges($time_intervals);
        Edge::sortEdgesByInstantAndIsStart($edges);

        $result = [];
        $counter = 0;
        $start = null;
        foreach ($edges as $edge) {
            if ($edge->isStart) {
                $counter++;
                if ($counter == 1) {
                    $start = $edge->instant;
                }
            } else {
                $counter--;
                if ($counter == 0) {
                    $result[] = new TimeInterval($start, $edge->instant);
                }
            }
        }

        return $result;
    }
}