<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
use Yolisses\TimeConstraints\TimeConstraint;

/**
 * Time constraint for a specific time of day. E.g. only from 10:00:00 to
 * 16:00:00.
 */
class TimeOfDayTimeConstraint extends TimeConstraint
{
    /**
     * @param string $startTime e.g. `'10:00:00'`
     * @param string $endTime e.g. `'16:00:00'`
     */
    public function __construct(public string $startTime, public string $endTime)
    {
    }

    static function setTime(\DateTimeImmutable $dateTimeImmutable, string $time): \DateTimeImmutable
    {
        $timeAsDateTime = new \DateTimeImmutable($time);
        return $dateTimeImmutable->setTime(
            $timeAsDateTime->format('H'),
            $timeAsDateTime->format('i'),
            $timeAsDateTime->format('s'),
            $timeAsDateTime->format('u'),
        );
    }

    public function getSequence(Period $clampPeriod): Sequence
    {
        $periods = [];

        $startDate = $clampPeriod->startDate;
        $currentDate = self::setTime($startDate, $this->startTime);

        while ($currentDate < $clampPeriod->endDate) {
            $periodEnd = self::setTime($currentDate, $this->endTime);

            $periods[] = Period::fromDate($currentDate, $periodEnd);

            $currentDate = $currentDate->modify('+1 day');
        }

        $sequence = new Sequence(...$periods);

        return $this->clampSequence($sequence, $clampPeriod);
    }
}