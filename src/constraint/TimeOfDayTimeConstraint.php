<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Constraint\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeInterval;

/**
 * Time constraint for a specific time of day. E.g. only from 10:00:00 to 12:00:00.
 */
class TimeOfDayTimeConstraint extends TimeConstraint
{
    /**
     * @param \DateTime $time_start e.g. `new DateTime('10:00:00')`
     * @param \DateTime $time_end e.g. `new DateTime('12:00:00')`
     */
    public function __construct(public \DateTime $time_start, public \DateTime $time_end)
    {
    }

    static function getCloneWithTime(\DateTime $dateTime, \DateTime $time): \DateTime
    {
        $dateTime = clone $dateTime;
        $dateTime->setTime(
            $time->format('H'),
            $time->format('i'),
            $time->format('s'),
            $time->format('u'),
        );
        return $dateTime;
    }

    public function getIntervals(\DateTime $start_instant, \DateTime $end_instant): array
    {
        $intervals = [];

        $current_instant = self::getCloneWithTime($start_instant, $this->time_start);

        while ($current_instant < $end_instant) {
            $interval_end = self::getCloneWithTime($current_instant, $this->time_end);

            $intervals[] = new TimeInterval(clone $current_instant, $interval_end);

            $current_instant->modify('+1 day');
        }


        return $this->clampIntervals($intervals, $start_instant, $end_instant);
    }
}