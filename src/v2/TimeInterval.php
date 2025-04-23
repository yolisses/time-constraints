<?php

namespace Yolisses\TimeConstraints\V2;

class TimeInterval
{
    private \DateTimeImmutable $start;
    private \DateTimeImmutable $end;

    public function __construct(\DateTimeImmutable $start, \DateTimeImmutable $end)
    {
        if ($start > $end) {
            throw new \InvalidArgumentException("Start time must be before end time");
        }
        $this->start = $start;
        $this->end = $end;
    }

    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): \DateTimeImmutable
    {
        return $this->end;
    }

    public function overlaps(TimeInterval $other): bool
    {
        return $this->start <= $other->end && $this->end >= $other->start;
    }

    public function contains(TimeInterval $other): bool
    {
        return $this->start <= $other->start && $this->end >= $other->end;
    }
}
