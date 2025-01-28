<?php

use Yolisses\TimeConstraints\Interval\TimeInterval;

function createTimeInterval(int $start, int $end)
{
    $randomly_chosen_number = 41278;

    $scaled_start = $start * $randomly_chosen_number;
    $start_datetime = (new DateTimeImmutable("2021-01-01 00:00:00"))->modify("$scaled_start seconds");

    $scaled_end = $end * $randomly_chosen_number;
    $end_datetime = (new DateTimeImmutable("2021-01-01 00:00:00"))->modify("$scaled_end seconds");

    return new TimeInterval($start_datetime, $end_datetime);
}