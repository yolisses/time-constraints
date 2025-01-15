<?php

namespace Yolisses\TimeConstraints\Interval;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class Edge
{
    public function __construct(public \DateTime $instant, public bool $isStart)
    {
    }
}

class TimeIntervalsUnion
{
    /**
     * @param array<TimeInterval> $time_intervals
     * @return array<TimeInterval>
     */
    static function unionTimeIntervals(array $time_intervals)
    {
        // Get edges
        $edges = [];
        foreach ($time_intervals as $time_interval) {
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

        print_r($edges);

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