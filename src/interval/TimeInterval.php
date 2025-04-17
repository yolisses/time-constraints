<?php
namespace Yolisses\TimeConstraints\Interval;

/**
 * Represents a time interval between two defined instants. It's conceptually
 * different from a `DateInterval`, which represents a duration.
 */
class TimeInterval
{
    public function __construct(
        public \DateTimeImmutable $start,
        public \DateTimeImmutable $end,
        public bool $include_start,
        public bool $include_end,
    ) {
        if (!($end >= $start)) {
            throw new \InvalidArgumentException('End must be equals or greater than start');
        }
    }

    public static function fromStrings(
        string $start,
        string $end,
        bool $include_start,
        bool $include_end,
    ): TimeInterval {
        return new TimeInterval(
            new \DateTimeImmutable($start),
            new \DateTimeImmutable($end),
            $include_start,
            $include_end,
        );
    }

    public function getDuration(): int
    {
        return $this->end->getTimestamp() - $this->start->getTimestamp();
    }
}
