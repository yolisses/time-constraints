<?php
namespace Yolisses\TimeConstraints\Constraint;

use DateTime;
use PHPUnit\Framework\TestCase;

class WeekdaysTimeConstraintTest extends TestCase
{
    public function testGetInitialWeekday()
    {
        $constraint = new WeekdaysTimeConstraint();
        $some_saturday = new DateTime('0001-01-01 00:00:00');
        $this->assertEquals($constraint->getInitialWeekday($some_saturday), new DateTime('0001-01-02 00:00:00'));
    }
}