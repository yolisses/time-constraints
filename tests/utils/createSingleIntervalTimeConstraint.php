<?php

use Yolisses\TimeConstraints\SinglePeriodTimeConstraint;

require_once __DIR__ . '/createTimePeriod.php';

function createSinglePeriodTimeConstraint(int $start, int $end): SinglePeriodTimeConstraint
{
    $timePeriod = createTimePeriod($start, $end);
    return new SinglePeriodTimeConstraint($timePeriod);
}