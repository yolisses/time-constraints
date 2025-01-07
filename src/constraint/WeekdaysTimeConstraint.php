<?php
namespace Yolisses\TimeConstraints\Constraint;

use DateTime;
use Yolisses\TimeConstraints\Interval\CompositeTimeInterval;
use Yolisses\TimeConstraints\Interval\SimpleTimeInterval;
use Yolisses\TimeConstraints\Interval\TimeInterval;

class WeekdaysTimeConstraint extends TimeConstraint
{
    static function getInitialWeekday(DateTime $start_instant): DateTime
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
        $initial_weekday = self::getInitialWeekday($start_instant);
        $final_weekday = self::getInitialWeekday($end_instant);

        $intervals = new CompositeTimeInterval();
        $current_weekday = clone $initial_weekday;
        while ($current_weekday < $final_weekday) {
            $weekday = $current_weekday->format('N');
            if ($weekday < 6) {
                $intervals->add(new SimpleTimeInterval($current_weekday, $current_weekday->modify('+1 day')));
            }
            $current_weekday->modify('+1 day');
        }

        return $intervals;
    }
}