<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\TimeConstraint;
use Yolisses\TimeConstraints\Period\TimePeriod;

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

    static function getCloneWithTime(\DateTimeImmutable $dateTimeImmutable, string $time): \DateTimeImmutable
    {
        $timeAsDateTime = new \DateTimeImmutable($time);
        return $dateTimeImmutable->setTime(
            $timeAsDateTime->format('H'),
            $timeAsDateTime->format('i'),
            $timeAsDateTime->format('s'),
            $timeAsDateTime->format('u'),
        );
    }

    public function getPeriods(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        $periods = [];

        $current_instant = self::getCloneWithTime($start_instant, $this->time_start);

        while ($current_instant < $end_instant) {
            $period_end = self::getCloneWithTime($current_instant, $this->time_end);

            $periods[] = new TimePeriod(clone $current_instant, $period_end);

            $current_instant = $current_instant->modify('+1 day');
        }


        return $this->clampPeriods($periods, $start_instant, $end_instant);
    }
}