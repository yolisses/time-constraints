<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Interval\TimeInterval;

/**
 * Time constraint for specific days of the week. E.g. only Mondays, Wednesdays
 * and Saturdays.
 *
 * The days of week are in numeric format, as in `$date->format('w')`, where 0
 * is Monday and 6 is Sunday.
 */
class DaysOfWeekTimeConstraint extends TimeConstraint
{
    public function __construct(public array $days_of_week)
    {
    }

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        $intervals = [];

        $current_instant = $start_instant->setTime(0, 0, 0);

        while ($current_instant < $end_instant) {
            $current_day_of_week = (int) $current_instant->format('w');

            if (in_array($current_day_of_week, $this->days_of_week)) {
                $interval_start = clone $current_instant;
                $interval_end = clone $current_instant;
                $interval_end = $interval_end->modify('+1 day');

                $intervals[] = new TimeInterval($interval_start, $interval_end);
            }

            $current_instant = $current_instant->modify('+1 day');
        }

        return $this->clampIntervals($intervals, $start_instant, $end_instant);
    }
}