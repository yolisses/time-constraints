<?php
namespace Yolisses\TimeConstraints\Interval;

class EmptyTimeInterval implements TimeInterval
{
    public function union(TimeInterval $time_interval): TimeInterval
    {
        return clone $time_interval;
    }

    public function intersection(TimeInterval $time_interval): TimeInterval
    {
        return new EmptyTimeInterval;
    }

    public function difference(TimeInterval $time_interval): TimeInterval
    {
        return new EmptyTimeInterval;
    }
}