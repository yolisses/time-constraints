<?php
namespace Yolisses\TimeConstraints\Interval;

class SimpleTimeInterval
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
}
