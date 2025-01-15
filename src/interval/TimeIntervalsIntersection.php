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
        $edges = [];
        foreach ($time_intervals_1 as $time_interval) {
            $edges[] = new Edge($time_interval->start, true);
            $edges[] = new Edge($time_interval->end, false);
        }
        foreach ($time_intervals_2 as $time_interval) {
            $edges[] = new Edge($time_interval->start, true);
            $edges[] = new Edge($time_interval->end, false);
        }

        // Sort edges by instant, using start edges before end edges in case of tie
        usort($edges, function ($a, $b) {
            if ($a->instant == $b->instant) {
                return $a->isStart ? -1 : 1;
            }
            return $a->instant < $b->instant ? -1 : 1;
        });

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
                if ($counter == 1) {
                    $result[] = new TimeInterval($start, $edge->instant);
                }
            }
        }

        return $result;
    }
}