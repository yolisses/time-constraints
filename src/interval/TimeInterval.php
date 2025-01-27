<?php
namespace Yolisses\TimeConstraints\Interval;

/**
 * Represents a time interval between two defined instants. It's conceptually
 * different from a `DateInterval`, which represents a duration.
 */
class TimeInterval
{
    public function __construct(public \DateTime $start, public \DateTime $end)
    {
        if (!($end >= $start)) {
            throw new \InvalidArgumentException('End must be equals or greater than start');
        }
    }

    public function getDuration(): int
    {
        return $this->end->getTimestamp() - $this->start->getTimestamp();
    }
}
