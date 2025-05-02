<?php
namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;

class WeekdaysTimeConstraint extends TimeConstraint
{
    static function getIsWeekend(\DateTimeImmutable $dateTimeImmutable)
    {
        $weekDay = $dateTimeImmutable->format('N');
        return $weekDay == 6 || $weekDay == 7;
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        $periods = [];
        $startDate = $clampPeriod->startDate;

        if (self::getIsWeekend($startDate)) {
            $startDate = $startDate->modify('next monday');
        }

        while ($startDate < $clampPeriod->endDate) {
            $nextSaturday = $startDate->modify('next saturday');
            $endDate = min($nextSaturday, $clampPeriod->endDate);
            $period = Period::fromDate($startDate, $endDate);
            $periods[] = $period;

            $startDate = $nextSaturday->modify('next monday');
        }

        return new Sequence(...$periods);
    }
}