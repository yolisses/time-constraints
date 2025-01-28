<?php

use Yolisses\TimeConstraints\Interval\TimeInterval;

function createTimeInterval(int $start, int $end)
{
    return TimeInterval::fromStrings("2001-01-$start", "2001-01-$end");
}