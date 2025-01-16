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
     * @param string $time_start e.g. `'10:00:00'`
     * @param string $time_end e.g. `'12:00:00'`
     */
    public function __construct(public string $time_start, public string $time_end)
    {
    }

    static function getCloneWithTime(\DateTime $dateTime, string $time): \DateTime
    {
        $timeAsDateTime = new \DateTime($time);
        $dateTime = clone $dateTime;
        $dateTime->setTime(
            $timeAsDateTime->format('H'),
            $timeAsDateTime->format('i'),
            $timeAsDateTime->format('s'),
            $timeAsDateTime->format('u'),
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