<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;

/**
 * Time constraint for specific days of the week. E.g. only Mondays, Wednesdays
 * and Saturdays.
 *
 * The days of week are in numeric format, as in `$date->format('w')`, where 0
 * is Monday and 6 is Sunday.
 */
class SpecificDaysOfWeekTimeConstraint extends TimeConstraint
{
    public function __construct(public array $days_of_week)
    {
    }

    public function getIntervals(\DateTime $start_instant, \DateTime $end_instant): array
    {
        $intervals = [];

        $current_instant = clone $start_instant;
        $current_instant->setTime(0, 0, 0);
        while ($current_instant < $end_instant) {
            $next_day = clone $current_instant;
            $next_day->modify('+1 day');

            $day_of_week = (int) $current_instant->format('w');
            if (in_array($day_of_week, $this->days_of_week)) {
                $intervals[] = new TimeInterval($current_instant, $next_day);
            }

            $current_instant = $next_day;
        }

        return $this->clampIntervals($intervals, $start_instant, $end_instant);
    }
}