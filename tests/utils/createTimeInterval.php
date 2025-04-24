<?php

require_once __DIR__ . '/createDateTime.php';

use Yolisses\TimeConstraints\Interval\TimeInterval;

function createTimeInterval(int $time_1, int $time_2): TimeInterval
{
    return new TimeInterval(
        createDateTime($time_1),
        createDateTime($time_2),
    );
}