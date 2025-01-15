<?php

namespace Yolisses\TimeConstraints\Interval;

class TimeIntervalsIntersection
{
    /**
     * @param array<TimeInterval> $time_intervals_1
     * @param array<TimeInterval> $time_intervals_2
     * @return array<TimeInterval>
     */
    static function intersectionTimeIntervals(array $time_intervals_1, array $time_intervals_2)
    {
        // Ensure no overlapping intervals
        $time_intervals_1 = TimeIntervalsUnion::unionTimeIntervals($time_intervals_1);
        $time_intervals_2 = TimeIntervalsUnion::unionTimeIntervals($time_intervals_2);

        // Get edges
        $edges = Edge::getTimeIntervalsEdges(array_merge($time_intervals_1, $time_intervals_2));
        Edge::sortEdgesByInstantAndIsStart($edges);

        $result = [];
        $counter = 0;
        $start = null;
        foreach ($edges as $edge) {
            if ($edge->isStart) {
                $counter++;
                if ($counter == 2) {
                    $start = $edge->instant;
                }
            } else {
                $counter--;
                // The inequality is to avoid adding an empty interval
                if ($counter == 1 && $edge->instant != $start) {
                    $result[] = new TimeInterval($start, $edge->instant);
                }
            }
        }

        return $result;
    }
}