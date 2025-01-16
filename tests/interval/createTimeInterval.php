<?php

use Yolisses\TimeConstraints\Interval\TimeInterval;

function createTimeInterval(int $start, int $end)
{
    return new TimeInterval(
        new \DateTime("0001-01-$start"),
        new \DateTime("0001-01-$end")
    );
}