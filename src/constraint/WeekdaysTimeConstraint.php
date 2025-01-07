<?php
namespace Yolisses\TimeConstraints\Constraint;

use DateTime;
use Yolisses\TimeConstraints\Interval\CompositeTimeInterval;
use Yolisses\TimeConstraints\Interval\SimpleTimeInterval;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class WeekdaysTimeConstraint extends TimeConstraint
{
    function getInitialWeekday(DateTime $start_instant): DateTime
    {
        $initial_weekday = clone $start_instant;

        $day_of_week = $initial_weekday->format('N');
        if ($day_of_week == 6) {
            $initial_weekday->modify('+2 day');
        } elseif ($day_of_week == 7) {
            $initial_weekday->modify('+1 day');
        }

        if ($day_of_week == 6 || $day_of_week == 7) {
            $initial_weekday->setTime(0, 0, 0, 0);
        }

        return $initial_weekday;
    }

    /**
     * Returns the time interval that occurs in weekdays between the start and end instants.
     * @param \DateTime $start_instant
     * @param \DateTime $end_instant
     * @return \Yolisses\TimeConstraints\Interval\TimeInterval
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