<?php

use Yolisses\TimeConstraints\V2\TimeInterval;

class UnionEdge
{
    public function __construct(
        public $instant,
        public bool $is_start,
        public bool $is_included
    ) {
    }
}

class TimeIntervalUnion
{
    /**
     * @param TimeInterval[] $time_intervals
     * @return TimeInterval[]
     */
    public static function union(array $time_intervals): array
    {
        $result = [];
        if (empty($time_intervals)) {
            return $result;
        }

        usort($time_intervals, function (TimeInterval $time_interval_1, TimeInterval $time_interval_2) {
            return $time_interval_1->start <=> $time_interval_2->end;
        });

        $end = $time_intervals[0]->end;
        $start = $time_intervals[0]->start;
        $end_is_included = $time_intervals[0]->end_is_included;
        $start_is_included = $time_intervals[0]->start_is_included;

        foreach ($time_intervals as $time_interval) {
            // ──
            //     ──
            // ──  ──
            if ($time_interval->start > $end) {
                $result[] = new TimeInterval($start, $end, $start_is_included, $end_is_included);

                $end = $time_interval->end;
                $start = $time_interval->start;
                $end_is_included = $time_interval->end_is_included;
                $start_is_included = $time_interval->start_is_included;
            }

            // ──
            //   ──
            if ($end == $time_interval->start) {
                // ─○
                //  ○─
                // ─○─
                if (!$end_is_included && !$time_interval->start_is_included) {
                    $result[] = new TimeInterval($start, $end, $start_is_included, $end_is_included);

                    $end = $time_interval->end;
                    $start = $time_interval->start;
                    $end_is_included = $time_interval->end_is_included;
                    $start_is_included = $time_interval->start_is_included;
                } else {
                    if ($end < $time_interval->end) {
                        $end = $time_interval->end;
                        $end_is_included = $time_interval->end_is_included;
                    }
                }
            }
        }
    }
}

// ●─○
//   ●─●
// ●───●
