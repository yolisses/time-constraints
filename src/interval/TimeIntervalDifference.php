<?php

namespace Yolisses\TimeConstraints\Interval;

class TimeIntervalDifference
{
    /**
     * Computes the difference of two arrays of intervals
     * @param TimeInterval[] $intervals1
     * @param TimeInterval[] $intervals2
     * @return TimeInterval[]
     */
    public static function difference(array $intervals1, array $intervals2): array
    {
        if (empty($intervals1)) {
            return [];
        }
        if (empty($intervals2)) {
            return $intervals1;
        }

        // Sort intervals by start time
        $sortIntervals = function (array $intervals) {
            usort($intervals, function (TimeInterval $a, TimeInterval $b) {
                $startA = $a->getStart()->getTimestamp();
                $startB = $b->getStart()->getTimestamp();
                if ($startA !== $startB) {
                    return $startA <=> $startB;
                }
                return $a->getEnd()->getTimestamp() <=> $b->getEnd()->getTimestamp();
            });
            return $intervals;
        };

        $intervals1 = $sortIntervals($intervals1);
        $intervals2 = $sortIntervals($intervals2);

        $result = [];

        foreach ($intervals1 as $interval1) {
            $currentStart = $interval1->getStart();
            $currentStartIncluded = $interval1->getStartIsIncluded();
            $end = $interval1->getEnd();
            $endIncluded = $interval1->getEndIsIncluded();

            $remaining = true;

            foreach ($intervals2 as $interval2) {
                // If interval2 ends before currentStart or starts after end,
                // skip
                if ($interval2->getEnd() < $currentStart || $interval2->getStart() > $end) {
                    continue;
                }

                // Handle case where interval2 starts at or after currentStart
                if ($interval2->getStart() >= $currentStart) {
                    $newEnd = $interval2->getStart();
                    // If interval2 starts exactly at interval1's end and
                    // interval2's start is included, exclude the endpoint;
                    // otherwise, use the opposite of interval2's start
                    // inclusion
                    $newEndIncluded = ($newEnd == $end && $interval2->getStartIsIncluded()) ? false : !$interval2->getStartIsIncluded();
                    // Only add interval if it has positive duration or is a
                    // valid single point
                    if ($currentStart < $newEnd || ($currentStart == $newEnd && $currentStartIncluded && $newEndIncluded)) {
                        $result[] = new TimeInterval($currentStart, $newEnd, $currentStartIncluded, $newEndIncluded);
                    }
                }

                // Update currentStart to after interval2's end
                if ($interval2->getEnd() > $currentStart) {
                    $currentStart = $interval2->getEnd();
                    $currentStartIncluded = !$interval2->getEndIsIncluded();
                }

                // If currentStart exceeds end, we're done with this interval1
                if ($currentStart > $end) {
                    $remaining = false;
                    break;
                }
            }

            // If there's still a remaining segment, add it
            if ($remaining && ($currentStart < $end || ($currentStart == $end && $currentStartIncluded && $endIncluded))) {
                $result[] = new TimeInterval($currentStart, $end, $currentStartIncluded, $endIncluded);
            }
        }

        // Remove any invalid intervals and sort result
        $result = array_filter($result, function (TimeInterval $interval) {
            return $interval->getStart() < $interval->getEnd() ||
                ($interval->getStart() == $interval->getEnd() &&
                    $interval->getStartIsIncluded() &&
                    $interval->getEndIsIncluded());
        });

        return $sortIntervals($result);
    }
}