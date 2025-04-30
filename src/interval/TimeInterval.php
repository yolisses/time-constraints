<?php

namespace Yolisses\TimeConstraints\Interval;

/**
 * Represents a time interval between two defined instants. It is conceptually
 * different from a `DateInterval`, which represents a duration.
 *
 * It is an immutable stateless class. It allows using `$interval1 ==
 * $interval2` and `assertEquals($interval1, $interval2)`, which is very useful
 * in tests.
 */
class TimeInterval
{
    public function __construct(
        private \DateTimeImmutable $start,
        private \DateTimeImmutable $end,
        private bool $start_is_included,
        private bool $end_is_included,
    ) {
        if ($start > $end) {
            throw new \InvalidArgumentException("The start time must be equals or earlier than the end time.");
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

    public function getStartIsIncluded(): bool
    {
        return $this->start_is_included;
    }

    public function getEndIsIncluded(): bool
    {
        return $this->end_is_included;
    }

    public function overlaps(TimeInterval $other): bool
    {
        return $this->start <= $other->end && $this->end >= $other->start;
    }

    public function contains(TimeInterval $other): bool
    {
        return $this->start <= $other->start && $this->end >= $other->end;
    }

    public static function fromStrings(string $start, string $end, bool $startIsIncluded, bool $endIsIncluded): TimeInterval
    {
        return new TimeInterval(
            new \DateTimeImmutable($start),
            new \DateTimeImmutable($end),
            $startIsIncluded,
            $endIsIncluded
        );
    }

    public function getDuration(): int
    {
        return $this->end->getTimestamp() - $this->start->getTimestamp();
    }
}
