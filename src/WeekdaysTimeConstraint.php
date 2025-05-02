<?php
namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Period\TimePeriod;

class WeekdaysTimeConstraint extends TimeConstraint
{
    static function getIsWeekend(\DateTimeImmutable $dateTimeImmutable)
    {
        $weekDay = $dateTimeImmutable->format('N');
        return $weekDay == 6 || $weekDay == 7;
    }

    public function getSequence(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): Sequence
    {
        $periods = [];
        $current_instant = clone $start_instant;

        if (self::getIsWeekend($current_instant)) {
            $current_instant = $current_instant->modify('next monday');
        }

        while ($current_instant < $end_instant) {
            $next_saturday = $current_instant->modify('next saturday');
            $period_end = min($next_saturday, $end_instant);
            $period = new TimePeriod($current_instant, $period_end);
            $periods[] = $period;

            $current_instant = $next_saturday->modify('next monday');
        }

        return $periods;
    }
}