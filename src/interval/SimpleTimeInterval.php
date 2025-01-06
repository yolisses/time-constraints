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

    public function getIsBefore(SimpleTimeInterval $simple_time_interval): bool
    {
        return $this->end < $simple_time_interval->start;
    }

    public function getIsAfter(SimpleTimeInterval $simple_time_interval): bool
    {
        return $this->start > $simple_time_interval->end;
    }

    public function getIsEqual(SimpleTimeInterval $simple_time_interval): bool
    {
        return $this->start == $simple_time_interval->start
            && $this->end == $simple_time_interval->end;
    }

    public function getIsIntersecting(SimpleTimeInterval $simple_time_interval): bool
    {
        return !$this->getIsBefore($simple_time_interval)
            && !$this->getIsAfter($simple_time_interval);
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

    private function intersection_with_simple_time_interval(SimpleTimeInterval $simple_time_interval): TimeInterval
    {
        if (
            $this->end < $simple_time_interval->start ||
            $this->start > $simple_time_interval->end
        ) {
            return new EmptyTimeInterval();
        }

        return new SimpleTimeInterval(
            max($this->start, $simple_time_interval->start),
            min($this->end, $simple_time_interval->end)
        );
    }

    function intersection(TimeInterval $time_interval): TimeInterval
    {
        if ($time_interval instanceof SimpleTimeInterval) {
            return $this->intersection_with_simple_time_interval($time_interval);
        } else {
            return $time_interval->intersection($this);
        }
    }

    function difference_with_simple_time_interval(SimpleTimeInterval $simple_time_interval): TimeInterval
    {
        if (
            $this->end < $simple_time_interval->start ||
            $this->start > $simple_time_interval->end
        ) {
            return new SimpleTimeInterval($this->start, $this->end);
        }

        if ($this->start < $simple_time_interval->start) {
            return new SimpleTimeInterval($this->start, $simple_time_interval->start);
        }

        return new SimpleTimeInterval($simple_time_interval->end, $this->end);
    }

    function difference(TimeInterval $time_interval): TimeInterval
    {
        if ($time_interval instanceof SimpleTimeInterval) {
            return $this->difference_with_simple_time_interval($time_interval);
        } else {
            return $time_interval->difference($this);
        }
    }
}
