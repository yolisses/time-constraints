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