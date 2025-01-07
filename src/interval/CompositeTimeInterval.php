<?php
namespace Yolisses\TimeConstraints\Interval;

class CompositeTimeInterval implements TimeInterval
{
    /**
     * Time intervals that compound this interval
     * @var TimeInterval[]
     */
    public array $time_intervals;

    /**
     * @param TimeInterval[] $time_intervals
     */
    public function __construct(array $time_intervals)
    {
        $this->time_intervals = $time_intervals;
    }


    function union(TimeInterval $time_interval): TimeInterval
    {
    }

    function intersection(TimeInterval $time_interval): TimeInterval
    {
    }

    function difference(TimeInterval $time_interval): TimeInterval
    {
    }
}