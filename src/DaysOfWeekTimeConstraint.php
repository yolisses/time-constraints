<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;

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

    public function getSequence(Period $clampPeriod): Sequence
    {
        $periods = [];

        $current_instant = $start_instant->setTime(0, 0, 0);

        while ($current_instant < $end_instant) {
            $current_day_of_week = (int) $current_instant->format('w');

            if (in_array($current_day_of_week, $this->days_of_week)) {
                $period_start = clone $current_instant;
                $period_end = clone $current_instant;
                $period_end = $period_end->modify('+1 day');

                $periods[] = new TimePeriod($period_start, $period_end);
            }

            $current_instant = $current_instant->modify('+1 day');
        }

        return $this->clampSequence($sequence, $clampPeriod);
    }
}