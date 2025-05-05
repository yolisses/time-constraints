<?php

use League\Period\Bounds;
use Yolisses\TimeConstraints\SinglePeriodTimeConstraint;

require_once __DIR__ . '/createPeriod.php';

function createSinglePeriodTimeConstraint(int $start, int $end, Bounds $bounds = Bounds::IncludeStartExcludeEnd): SinglePeriodTimeConstraint
{
    $period = createPeriod($start, $end, $bounds);
    return new SinglePeriodTimeConstraint($period);
}