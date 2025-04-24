<?php

namespace Yolisses\TimeConstraints\Interval;

/**
 * Represents a time interval between two defined instants. It's conceptually
 * different from a `DateInterval`, which represents a duration.
 */
class TimeInterval
{
    public function __construct(
        private \DateTimeImmutable $start,
        private \DateTimeImmutable $end
    ) {
        if ($start > $end) {
            throw new \InvalidArgumentException("Start time must be before end time");
        }
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

    public static function fromStrings(string $start, string $end): TimeInterval
    {
        return new TimeInterval(
            new \DateTimeImmutable($start),
            new \DateTimeImmutable($end)
        );
    }

    public function getDuration(): int
    {
        return $this->end->getTimestamp() - $this->start->getTimestamp();
    }
}
