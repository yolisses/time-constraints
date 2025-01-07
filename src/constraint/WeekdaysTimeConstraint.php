<?php

use Yolisses\TimeConstraints\SimpleTimeInterval;
use Yolisses\TimeConstraints\TimeInterval;

class WeekdaysTimeConstraint extends TimeConstraint
{
    function getInitialWeekday(DateTime $start_instant): DateTime
    {
        $current_instant = clone $start_instant;
        $current_instant->modify('-1 weekday')->modify('+1 weekday');
        return $current_instant;
    }

    /**
     * Returns the time interval that occurs in weekdays between the start and end instants.
     * @param DateTime $start_instant
     * @param DateTime $end_instant
     * @return TimeInterval
     */
    public function getIntervals(DateTime $start_instant, DateTime $end_instant): TimeInterval
    {
        $intervals = [];
        $current_instant = clone $start_instant;
        while ($current_instant < $end_instant) {
            if ($current_instant->format('N') < 6) {
                $start = clone $current_instant;
                $start->setTime(9, 0);
                $end = clone $current_instant;
                $end->setTime(17, 0);
                $intervals[] = new SimpleTimeInterval($start, $end);
            }
            $current_instant->modify('+1 day');
        }
        return new CompositeTimeInterval($intervals);
    }
}