<?php

namespace Yolisses\TimeConstraints;

class SimpleTimeInterval implements TimeInterval
{
    public function __construct(public \DateTime $start, public \DateTime $end)
    {
        if ($start > $end) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }
    }

    private function union_with_simple_time_interval(SimpleTimeInterval $simple_time_interval): TimeInterval
    {
        if (
            $this->end < $simple_time_interval->start ||
            $this->start > $simple_time_interval->end
        ) {
            return new CompositeTimeInterval([$this, $simple_time_interval]);
        }

        return new SimpleTimeInterval(
            min($this->start, $simple_time_interval->start),
            max($this->end, $simple_time_interval->end)
        );
    }

    function union(TimeInterval $time_interval): TimeInterval
    {
        if ($time_interval instanceof SimpleTimeInterval) {
            return $this->union_with_simple_time_interval($time_interval);
        } else {
            return $time_interval->union($this);
        }
    }

    function intersection(TimeInterval $time_interval): TimeInterval
    {
    }

    function difference(TimeInterval $time_interval): TimeInterval
    {
    }
}
