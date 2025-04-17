<?php

namespace Yolisses\TimeConstraints\Interval;

class Edge
{
    public function __construct(
        public \DateTimeImmutable $instant,
        public bool $isStart,
        public bool $is_included
    ) {
    }

    /**
     * @param array<TimeInterval> $time_intervals
     */
    static function getTimeIntervalsEdges(array $time_intervals): array
    {
        $edges = [];
        foreach ($time_intervals as $time_interval) {
            $edges[] = new Edge($time_interval->start, true, $time_interval->include_start);
            $edges[] = new Edge($time_interval->end, false, $time_interval->include_end);
        }

        return $edges;
    }
}