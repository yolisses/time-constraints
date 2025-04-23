<?php

namespace Yolisses\TimeConstraints\V2;

class TimeIntervalOperations
{
    /**
     * Computes the union of an array of time intervals
     * @param TimeInterval[] $intervals
     * @return TimeInterval[]
     */
    public static function union(array $intervals): array
    {
        if (empty($intervals)) {
            return [];
        }

        // Sort intervals by start time
        usort($intervals, function ($a, $b) {
            return $a->start <=> $b->start ?: $b->end <=> $a->end;
        });

        $result = [];
        $current = clone $intervals[0];

        foreach ($intervals as $interval) {
            if (self::canMerge($current, $interval)) {
                $current = self::mergeIntervals($current, $interval);
            } else {
                $result[] = $current;
                $current = clone $interval;
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
        $i = 0;
        $j = 0;

        while ($i < count($intervals1) && $j < count($intervals2)) {
            $int1 = $intervals1[$i];
            $int2 = $intervals2[$j];

            // Find potential intersection
            $start = max($int1->start, $int2->start);
            $end = min($int1->end, $int2->end);

            // Check if it's a valid interval
            if ($start < $end || ($start == $end && self::isPointIncluded($int1, $int2, $start))) {
                $start_included = ($start == $int1->start ? $int1->start_is_included : true) &&
                    ($start == $int2->start ? $int2->start_is_included : true);
                $end_included = ($end == $int1->end ? $int1->end_is_included : true) &&
                    ($end == $int2->end ? $int2->end_is_included : true);
                $result[] = new TimeInterval($start, $end, $start_included, $end_included);
            }

            // Move to next interval
            if ($int1->end < $int2->end || ($int1->end == $int2->end && !$int1->end_is_included)) {
                $i++;
            } else {
                $j++;
            }
        }

        return $result;
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

        foreach ($intervals1 as $int1) {
            $current = [clone $int1];
            foreach ($intervals2 as $int2) {
                $temp = [];
                foreach ($current as $curr) {
                    // No overlap
                    if (
                        $curr->end < $int2->start || ($curr->end == $int2->start && !($curr->end_is_included && $int2->start_is_included)) ||
                        $int2->end < $curr->start || ($int2->end == $curr->start && !($int2->end_is_included && $curr->start_is_included))
                    ) {
                        $temp[] = $curr;
                        continue;
                    }

                    // Left part
                    if ($curr->start < $int2->start || ($curr->start == $int2->start && $curr->start_is_included && !$int2->start_is_included)) {
                        $end = $int2->start;
                        $end_included = !$int2->start_is_included;
                        $temp[] = new TimeInterval($curr->start, $end, $curr->start_is_included, $end_included);
                    }

                    // Right part
                    if ($curr->end > $int2->end || ($curr->end == $int2->end && $curr->end_is_included && !$int2->end_is_included)) {
                        $start = $int2->end;
                        $start_included = !$int2->end_is_included;
                        $temp[] = new TimeInterval($start, $curr->end, $start_included, $curr->end_is_included);
                    }
                }
                $current = $temp;
            }
            $result = array_merge($result, $current);
        }

        return self::union($result); // Normalize result
    }

    private static function canMerge(TimeInterval $int1, TimeInterval $int2): bool
    {
        return ($int1->end > $int2->start) ||
            ($int1->end == $int2->start && ($int1->end_is_included || $int2->start_is_included));
    }

    private static function mergeIntervals(TimeInterval $int1, TimeInterval $int2): TimeInterval
    {
        $start = min($int1->start, $int2->start);
        $start_included = $start == $int1->start ? $int1->start_is_included : $int2->start_is_included;

        $end = max($int1->end, $int2->end);
        $end_included = $end == $int1->end ? $int1->end_is_included : $int2->end_is_included;

        if ($int1->end == $int2->end && $int1->end_is_included != $int2->end_is_included) {
            $end_included = true;
        }

        return new TimeInterval($start, $end, $start_included, $end_included);
    }

    private static function isPointIncluded(TimeInterval $int1, TimeInterval $int2, \DateTimeImmutable $point): bool
    {
        $start_ok = ($point == $int1->start && $int1->start_is_included) &&
            ($point == $int2->start && $int2->start_is_included);
        $end_ok = ($point == $int1->end && $int1->end_is_included) &&
            ($point == $int2->end && $int2->end_is_included);
        return $start_ok || $end_ok;
    }
}
