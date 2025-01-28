<?php

use Yolisses\TimeConstraints\Interval\TimeInterval;

function createTimeInterval(int $start, int $end)
{
    return new TimeInterval(
        new \DateTimeImmutable("0001-01-$start"),
        new \DateTimeImmutable("0001-01-$end")
    );
}