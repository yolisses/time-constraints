<?php
namespace Yolisses\TimeConstraints;

class SimpleTimeInterval implements TimeInterval
{
    public function __construct(public DateTime $start, public DateTime $end)
    {
    }

    function unionEmpty(EmptyTimeInterval $time_interval): TimeInterval
    {
        return $this;
    }

    function unionSimple(SimpleTimeInterval $time_interval): TimeInterval
    {
        if ($this->start <= $time_interval->start && $time_interval->end <= $this->end) {
            return $this;
        }

        if ($time_interval->start <= $this->start && $this->end <= $time_interval->end) {
            return $time_interval;
        }

        return new CompoundTimeInterval([$this, $time_interval]);
    }

    public function union(TimeInterval $time_interval): TimeInterval
    {
        if ($time_interval instanceof EmptyTimeInterval) {
            return $this->unionEmpty($time_interval);
        } else if ($time_interval instanceof SimpleTimeInterval) {
            return $this->unionSimple($time_interval);
        } else {
            return new CompoundTimeInterval([$this, $time_interval]);
        }
    }
}