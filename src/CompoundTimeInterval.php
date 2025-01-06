<?php
namespace Yolisses\TimeConstraints;

class CompoundTimeInterval implements TimeInterval
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
}