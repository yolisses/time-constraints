<?php
namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class WeekdaysTimeConstraint extends TimeConstraint
{
    static function getIsWeekend(\DateTimeImmutable $dateTimeImmutable)
    {
        $weekDay = $dateTimeImmutable->format('N');
        return $weekDay == 6 || $weekDay == 7;
    }

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        $intervals = [];
        $current_instant = clone $start_instant;

        if (self::getIsWeekend($current_instant)) {
            $current_instant = $current_instant->modify('next monday');
        }

        while ($current_instant < $end_instant) {
            $next_saturday = $current_instant->modify('next saturday');
            $interval_end = min($next_saturday, $end_instant);
            $interval = new TimeInterval($current_instant, $interval_end);
            $intervals[] = $interval;

            $current_instant = $next_saturday->modify('next monday');
        }

        return $intervals;
    }
}