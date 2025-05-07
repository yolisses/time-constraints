<?php

use League\Period\Bounds;
use League\Period\Period;

require_once __DIR__ . '/createDateTime.php';

function createPeriod(int $time1, int $time2, Bounds $bounds = Bounds::IncludeStartExcludeEnd): Period
{
    return Period::fromDate(createDateTime($time1), createDateTime($time2), $bounds);
}