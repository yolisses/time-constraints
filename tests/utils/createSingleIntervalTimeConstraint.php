<?php

use Yolisses\TimeConstraints\SingleIntervalTimeConstraint;

require_once __DIR__ . '/createTimeInterval.php';

function createSingleIntervalTimeConstraint(int $start, int $end): SingleIntervalTimeConstraint
{
    $timeInterval = createTimeInterval($start, $end);
    return new SingleIntervalTimeConstraint($timeInterval);
}