<?php

use Yolisses\TimeConstraints\TimeInterval;

abstract class TimeConstraint
{
    abstract public function getIntervals(DateTime $start_instant, DateTime $end_instant): TimeInterval;
}