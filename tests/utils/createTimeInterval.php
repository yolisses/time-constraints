<?php

use Yolisses\TimeConstraints\Interval\TimeInterval;

require_once __DIR__ . '/createInstant.php';

function createTimeInterval(int $start, int $end, bool $include_start, bool $include_end)
{
    $start_datetime = createInstant($start);
    $end_datetime = createInstant($end);
    return new TimeInterval($start_datetime, $end_datetime, $include_start, $include_end);
}