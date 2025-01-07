<?php

namespace Yolisses\TimeConstraints\Constraint;

use DateTime;
use Yolisses\TimeConstraints\Interval\TimeInterval;

abstract class TimeConstraint
{
    abstract public function getIntervals(DateTime $start_instant, DateTime $end_instant): TimeInterval;
}