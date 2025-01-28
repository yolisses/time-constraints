<?php
namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class WeekdaysTimeConstraint extends TimeConstraint
{
    static function nextSaturday(\DateTimeImmutable $current_instant): \DateTimeImmutable
    {
        $next_saturday = clone $current_instant;
        $next_saturday->modify('next saturday');
        return $next_saturday;
    }

    static function nextMonday(\DateTimeImmutable $current_instant): \DateTimeImmutable
    {
        $next_monday = clone $current_instant;
        $next_monday->modify('next monday');
        return $next_monday;
    }

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
            $current_instant = self::nextMonday($current_instant);
        }

        while ($current_instant < $end_instant) {
            $next_saturday = self::nextSaturday($current_instant);
            $interval_end = min($next_saturday, $end_instant);
            $interval = new TimeInterval($current_instant, $interval_end);
            $intervals[] = $interval;

            $current_instant = self::nextMonday($next_saturday);
        }

        return $intervals;
    }
}