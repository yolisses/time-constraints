<?php

namespace Yolisses\TimeConstraints\Interval;

use StableSort;

class TimeIntervalsUnion
{
    /**
     * @param array<Edge> $edges
     */
    public static function sortEdges(array &$edges)
    {
        // false first
        StableSort::usort($edges, fn(Edge $a, Edge $b) => $a->is_included <=> $b->is_included);

        // true first
        StableSort::usort($edges, fn(Edge $a, Edge $b) => $b->isStart <=> $a->isStart);

        StableSort::usort($edges, fn(Edge $a, Edge $b) => $a->instant <=> $b->instant);
    }

    /**
     * @param array<TimeInterval> $time_intervals
     * @return array<TimeInterval>
     */
    static function unionTimeIntervals(array $time_intervals)
    {
        $edges = Edge::getTimeIntervalsEdges($time_intervals);
        self::sortEdges($edges);

        $result = [];
        $counter = 0;

        $start = null;
        $include_start = null;

        foreach ($edges as $edge) {
            if ($edge->isStart) {
                $counter++;
                if ($counter == 1) {
                    $start = $edge->instant;
                    $include_start = $edge->is_included;
                } else if ($edge->instant == $start) {
                    $include_start = $include_start || $edge->is_included;
                }
            } else {
                $counter--;
                if ($counter == 0) {
                    // TODO fix include_end
                    $result[] = new TimeInterval($start, $edge->instant, $include_start, true);
                }
            }
        }

        return $result;
    }
}