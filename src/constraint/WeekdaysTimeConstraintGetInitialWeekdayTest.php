<?php
namespace Yolisses\TimeConstraints\Constraint;

use DateTime;
use PHPUnit\Framework\TestCase;

class WeekdaysTimeConstraintGetInitialWeekdayTest extends TestCase
{
    public function testGetInitialWeekdayForMonday()
    {
        $some_time_at_monday = new DateTime('2018-01-01 05:00:00');
        $this->assertEquals(
            $some_time_at_monday,
            WeekdaysTimeConstraint::getInitialWeekday($some_time_at_monday)
        );
    }

    public function testGetInitialWeekdayForTuesday()
    {
        $some_time_at_tuesday = new DateTime('2018-01-02 05:00:00');
        $this->assertEquals(
            $some_time_at_tuesday,
            WeekdaysTimeConstraint::getInitialWeekday($some_time_at_tuesday)
        );
    }

    public function testGetInitialWeekdayForWednesday()
    {
        $some_time_at_wednesday = new DateTime('2018-01-03 05:00:00');
        $this->assertEquals(
            $some_time_at_wednesday,
            WeekdaysTimeConstraint::getInitialWeekday($some_time_at_wednesday)
        );
    }

    public function testGetInitialWeekdayForThursday()
    {
        $some_time_at_thursday = new DateTime('2018-01-04 05:00:00');
        $this->assertEquals(
            $some_time_at_thursday,
            WeekdaysTimeConstraint::getInitialWeekday($some_time_at_thursday)
        );
    }

    public function testGetInitialWeekdayForFriday()
    {
        $some_time_at_friday = new DateTime('2018-01-05 05:00:00');
        $this->assertEquals(
            $some_time_at_friday,
            WeekdaysTimeConstraint::getInitialWeekday($some_time_at_friday)
        );
    }

    public function testGetInitialWeekdayForSaturday()
    {
        $some_time_at_saturday = new DateTime('2018-01-06 05:00:00');
        $next_monday_start = new DateTime('2018-01-08 00:00:00');
        $this->assertEquals(
            $next_monday_start,
            WeekdaysTimeConstraint::getInitialWeekday($some_time_at_saturday)
        );
    }

    public function testGetInitialWeekdayForSunday()
    {
        $some_time_at_sunday = new DateTime('2018-01-07 05:00:00');
        $next_monday_start = new DateTime('2018-01-08 00:00:00');
        $this->assertEquals(
            $next_monday_start,
            WeekdaysTimeConstraint::getInitialWeekday($some_time_at_sunday)
        );
    }
}