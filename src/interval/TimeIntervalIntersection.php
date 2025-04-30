<?php

namespace Yolisses\TimeConstraints\Interval;

class TimeIntervalIntersection
{
    /**
     * Computes the intersection of two arrays of intervals
     * @param TimeInterval[] $intervals1
     * @param TimeInterval[] $intervals2
     * @return TimeInterval[]
     */
    public static function intersection(array $intervals1, array $intervals2): array
    {
        if (empty($intervals1) || empty($intervals2)) {
            return [];
        }

        $result = [];

        // Compare each interval from intervals1 with each from intervals2
        foreach ($intervals1 as $interval1) {
            foreach ($intervals2 as $interval2) {
                if ($interval1->overlaps($interval2)) {
                    // Find the intersection start and end
                    $start = max($interval1->getStart(), $interval2->getStart());
                    $end = min($interval1->getEnd(), $interval2->getEnd());

                    // Only include if start is not after end
                    if ($start <= $end) {
                        // Determine start inclusion
                        $startIsIncluded = false;
                        if ($interval1->getStart() == $interval2->getStart()) {
                            $startIsIncluded = $interval1->getStartIsIncluded() && $interval2->getStartIsIncluded();
                        } elseif ($interval1->getStart() > $interval2->getStart()) {
                            $startIsIncluded = $interval1->getStartIsIncluded();
                        } else {
                            $startIsIncluded = $interval2->getStartIsIncluded();
                        }

                        // Determine end inclusion
                        $endIsIncluded = false;
                        if ($interval1->getEnd() == $interval2->getEnd()) {
                            $endIsIncluded = $interval1->getEndIsIncluded() && $interval2->getEndIsIncluded();
                        } elseif ($interval1->getEnd() < $interval2->getEnd()) {
                            $endIsIncluded = $interval1->getEndIsIncluded();
                        } else {
                            $endIsIncluded = $interval2->getEndIsIncluded();
                        }

                        // Create new interval for the intersection
                        $result[] = new TimeInterval($start, $end, $startIsIncluded, $endIsIncluded);
                    }
                }
            }
        }

        // Sort intervals by start time, then by end time
        usort($result, function (TimeInterval $a, TimeInterval $b) {
            $startA = $a->getStart()->getTimestamp();
            $startB = $b->getStart()->getTimestamp();
            if ($startA !== $startB) {
                return $startA <=> $startB;
            }
            return $a->getEnd()->getTimestamp() <=> $b->getEnd()->getTimestamp();
        });

        return $result;
    }
}