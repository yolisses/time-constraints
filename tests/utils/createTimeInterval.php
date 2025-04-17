<?php

use Yolisses\TimeConstraints\Interval\TimeInterval;

require_once __DIR__ . '/randomly_chosen_number.php';

function createTimeInterval(int $start, int $end, bool $include_start, bool $include_end)
{
    $scaled_start = $start * RANDOMLY_CHOSEN_NUMBER;
    $start_datetime = (new DateTimeImmutable("2021-01-01 00:00:00"))->modify("$scaled_start seconds");

    $scaled_end = $end * RANDOMLY_CHOSEN_NUMBER;
    $end_datetime = (new DateTimeImmutable("2021-01-01 00:00:00"))->modify("$scaled_end seconds");

    return new TimeInterval($start_datetime, $end_datetime, $include_start, $include_end);
}