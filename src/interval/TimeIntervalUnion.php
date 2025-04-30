<?php

namespace Yolisses\TimeConstraints\Interval;

class TimeIntervalUnion
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

        // Sort intervals by start time, and by end time if start times are
        // equal
        usort($intervals, function (TimeInterval $a, TimeInterval $b) {
            $startA = $a->getStart()->getTimestamp();
            $startB = $b->getStart()->getTimestamp();
            if ($startA !== $startB) {
                return $startA <=> $startB;
            }
            return $a->getEnd()->getTimestamp() <=> $b->getEnd()->getTimestamp();
        });

        $result = [];
        $current = $intervals[0];

        foreach ($intervals as $interval) {
            // If current interval ends before the next one starts and endpoints
            // aren't included, or if there's a gap between non-included
            // endpoints, start a new interval
            if (
                $current->getEnd() < $interval->getStart() ||
                ($current->getEnd() == $interval->getStart() &&
                    !$current->getEndIsIncluded() && !$interval->getStartIsIncluded())
            ) {
                $result[] = $current;
                $current = $interval;
                continue;
            }

            // Handle equal start times: override current if interval includes
            // start and current does not
            if (
                $current->getStart() == $interval->getStart() &&
                !$current->getStartIsIncluded() && $interval->getStartIsIncluded()
            ) {
                $current = new TimeInterval(
                    $current->getStart(),
                    $current->getEnd(),
                    true,
                    $current->getEndIsIncluded()
                );
            }

            // Extend current interval if necessary
            if ($interval->getEnd() > $current->getEnd()) {
                $current = new TimeInterval(
                    $current->getStart(),
                    $interval->getEnd(),
                    $current->getStartIsIncluded(),
                    $interval->getEndIsIncluded()
                );
            } elseif ($interval->getEnd() == $current->getEnd()) {
                // If endpoints are equal, include endpoint if either interval
                // includes it
                $current = new TimeInterval(
                    $current->getStart(),
                    $current->getEnd(),
                    $current->getStartIsIncluded(),
                    $current->getEndIsIncluded() || $interval->getEndIsIncluded()
                );
            }
        }

        $result[] = $current;
        return $result;
    }
}