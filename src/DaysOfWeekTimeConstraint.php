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

        $currentDate = $clampPeriod->startDate->setTime(0, 0, 0);

        while ($currentDate < $clampPeriod->endDate) {
            $currentDayOfWeek = (int) $currentDate->format('w');

            if (in_array($currentDayOfWeek, $this->days_of_week)) {
                $startDate = clone $currentDate;
                $endDate = clone $currentDate;
                $endDate = $endDate->modify('+1 day');

                $periods[] = Period::fromDate($startDate, $endDate);
            }

            $currentDate = $currentDate->modify('+1 day');
        }

        var_dump($periods);
        $sequence = new Sequence(...$periods);

        return $this->clampSequence($sequence, $clampPeriod);
    }
}