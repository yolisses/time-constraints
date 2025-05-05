<?php

use League\Period\Bounds;
use League\Period\Period;

require_once __DIR__ . '/createDateTime.php';

function createPeriod(int $time_1, int $time_2, Bounds $bounds = Bounds::IncludeStartExcludeEnd): Period
{
    return Period::fromDate(createDateTime($time_1), createDateTime($time_2), $bounds);
}