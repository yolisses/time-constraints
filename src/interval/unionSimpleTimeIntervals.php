<?php

use Yolisses\TimeConstraints\Interval\SimpleTimeInterval;

/**
 * @param array<SimpleTimeInterval> $simple_time_intervals
 * @return array<SimpleTimeInterval>
 */
function unionSimpleTimeIntervals(array $simple_time_intervals)
{
    if (empty($simple_time_intervals)) {
        return [];
    }

    $starts = [];
    $ends = [];

    foreach ($simple_time_intervals as $interval) {
        $starts[] = $interval->start;
        $ends[] = $interval->end;
    }

    sort($starts);
    sort($ends);

    $result = [];
    $startIndex = 0;
    $endIndex = 0;
    $openIntervals = 0;

    while ($startIndex < count($starts)) {
        if ($starts[$startIndex] <= $ends[$endIndex]) {
            if ($openIntervals == 0) {
                $currentStart = $starts[$startIndex];
            }
            $openIntervals++;
            $startIndex++;
        } else {
            $openIntervals--;
            if ($openIntervals == 0) {
                $currentEnd = $ends[$endIndex];
                $result[] = new SimpleTimeInterval($currentStart, $currentEnd);
            }
            $endIndex++;
        }
    }

    return $result;
}