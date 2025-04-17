<?php

use Yolisses\TimeConstraints\Constraint\SingleIntervalTimeConstraint;

require_once __DIR__ . '/createTimeInterval.php';

function createSingleIntervalTimeConstraint(int $start, int $end, bool $include_start, bool $include_end): SingleIntervalTimeConstraint
{
    $timeInterval = createTimeInterval($start, $end, $include_start, $include_end);
    return new SingleIntervalTimeConstraint($timeInterval);
}