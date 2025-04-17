<?php

namespace Yolisses\TimeConstraints\Interval;

class Edge
{
    public function __construct(
        public \DateTimeImmutable $instant,
        public bool $isStart,
        public bool $isIncluded
    ) {
    }

    static function getTimeIntervalsEdges(array $time_intervals): array
    {
        $edges = [];
        foreach ($time_intervals as $time_interval) {
            $edges[] = new Edge($time_interval->start, true, $time_interval->includeStart);
            $edges[] = new Edge($time_interval->end, false, $time_interval->includeEnd);
        }

        return $edges;
    }

    /**
     * Sort edges by instant, with start edges before end edges in case of tie
     * @param array<Edge> $edges
     */
    static function sortEdgesByInstantAndIsStart(array &$edges)
    {
        usort($edges, function ($a, $b) {
            if ($a->instant == $b->instant) {
                return $a->isStart ? -1 : 1;
            }
            return $a->instant < $b->instant ? -1 : 1;
        });
    }
}