<?php
namespace Yolisses\TimeConstraints\Constraint;

use DateTime;
use PHPUnit\Framework\TestCase;

class WeekdaysTimeConstraintTest extends TestCase
{
    public function testGetInitialWeekdayMonday()
    {
        $constraint = new WeekdaysTimeConstraint();
        $some_time_at_monday = new DateTime('2018-01-01 05:00:00');
        $this->assertEquals($some_time_at_monday, $constraint->getInitialWeekday($some_time_at_monday));
    }

    public function testGetInitialWeekdayTuesday()
    {
        $constraint = new WeekdaysTimeConstraint();
        $some_time_at_tuesday = new DateTime('2018-01-02 05:00:00');
        $this->assertEquals($some_time_at_tuesday, $constraint->getInitialWeekday($some_time_at_tuesday));
    }

    public function testGetInitialWeekdayWednesday()
    {
        $constraint = new WeekdaysTimeConstraint();
        $some_time_at_wednesday = new DateTime('2018-01-03 05:00:00');
        $this->assertEquals($some_time_at_wednesday, $constraint->getInitialWeekday($some_time_at_wednesday));
    }

    public function testGetInitialWeekdayThursday()
    {
        $constraint = new WeekdaysTimeConstraint();
        $some_time_at_thursday = new DateTime('2018-01-04 05:00:00');
        $this->assertEquals($some_time_at_thursday, $constraint->getInitialWeekday($some_time_at_thursday));
    }

    public function testGetInitialWeekdayFriday()
    {
        $constraint = new WeekdaysTimeConstraint();
        $some_time_at_friday = new DateTime('2018-01-05 05:00:00');
        $this->assertEquals($some_time_at_friday, $constraint->getInitialWeekday($some_time_at_friday));
    }

    public function testGetInitialWeekdaySaturday()
    {
        $constraint = new WeekdaysTimeConstraint();
        $some_time_at_saturday = new DateTime('2018-01-06 05:00:00');
        $next_monday_start = new DateTime('2018-01-08 00:00:00');
        $this->assertEquals($next_monday_start, $constraint->getInitialWeekday($some_time_at_saturday));
    }

    public function testGetInitialWeekdaySunday()
    {
        $constraint = new WeekdaysTimeConstraint();
        $some_time_at_sunday = new DateTime('2018-01-07 05:00:00');
        $next_monday_start = new DateTime('2018-01-08 00:00:00');
        $this->assertEquals($next_monday_start, $constraint->getInitialWeekday($some_time_at_sunday));
    }
}