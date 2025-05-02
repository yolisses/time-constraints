<?php

require_once __DIR__ . '/createDateTime.php';

use Yolisses\TimeConstraints\Period\TimePeriod;

function createTimePeriod(int $time_1, int $time_2): TimePeriod
{
    return new TimePeriod(
        createDateTime($time_1),
        createDateTime($time_2),
    );
}