<?php

namespace Yolisses\TimeConstraints\Interval;

class TimeIntervalOperations
{
    /**
     * Computes the union of time intervals
     * @param TimeInterval[] $intervals
     * @return TimeInterval[]
     */
    public static function union(array $intervals): array
    {
        if (empty($intervals)) {
            return [];
        }

        // Sort intervals by start time
        usort($intervals, function (TimeInterval $a, TimeInterval $b) {
            return $a->getStart() <=> $b->getStart();
        });

        $result = [];
        $current = $intervals[0];

        foreach ($intervals as $interval) {
            if ($interval->getStart() <= $current->getEnd()) {
                // Overlapping or adjacent intervals, extend end if necessary
                if ($interval->getEnd() > $current->getEnd()) {
                    $current = new TimeInterval($current->getStart(), $interval->getEnd());
                }
            } else {
                // Non-overlapping interval, add current to result and start new
                $result[] = $current;
                $current = $interval;
            }
        }

        $result[] = $current;
        return $result;
    }

    /**
     * Computes the intersection of two arrays of time intervals
     * @param TimeInterval[] $intervals1
     * @param TimeInterval[] $intervals2
     * @return TimeInterval[]
     */
    public static function intersection(array $intervals1, array $intervals2): array
    {
        $result = [];

        foreach ($intervals1 as $interval1) {
            foreach ($intervals2 as $interval2) {
                if ($interval1->overlaps($interval2)) {
                    $start = max($interval1->getStart(), $interval2->getStart());
                    $end = min($interval1->getEnd(), $interval2->getEnd());
                    $result[] = new TimeInterval($start, $end);
                }
            }
        }

        // Sort and merge overlapping results
        return self::union($result);
    }

    /**
     * Computes the difference of two arrays of time intervals
     * @param TimeInterval[] $intervals1
     * @param TimeInterval[] $intervals2
     * @return TimeInterval[]
     */
    public static function difference(array $intervals1, array $intervals2): array
    {
        $result = [];

        foreach ($intervals1 as $interval1) {
            $currentIntervals = [new TimeInterval($interval1->getStart(), $interval1->getEnd())];

            foreach ($intervals2 as $interval2) {
                $tempIntervals = [];

                foreach ($currentIntervals as $current) {
                    // No overlap
                    if (!$current->overlaps($interval2)) {
                        $tempIntervals[] = $current;
                        continue;
                    }

                    // Add portion before intersection
                    if ($current->getStart() < $interval2->getStart()) {
                        $tempIntervals[] = new TimeInterval(
                            $current->getStart(),
                            min($current->getEnd(), $interval2->getStart())
                        );
                    }

                    // Add portion after intersection
                    if ($current->getEnd() > $interval2->getEnd()) {
                        $tempIntervals[] = new TimeInterval(
                            max($current->getStart(), $interval2->getEnd()),
                            $current->getEnd()
                        );
                    }
                }

                $currentIntervals = $tempIntervals;
            }

            $result = array_merge($result, $currentIntervals);
        }

        // Sort and merge overlapping results
        return self::union($result);
    }
}
