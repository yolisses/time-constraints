<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\V2\TimeInterval;

class TimeIntervalOperationsTestBase extends TestCase
{
    protected function createDateTime(int $second): DateTimeImmutable
    {
        return new DateTimeImmutable("2023-01-01 00:00:$second");
    }

    protected function createInterval(int $time_1, int $time_2): TimeInterval
    {
        return new TimeInterval(
            $this->createDateTime($time_1),
            $this->createDateTime($time_2),
        );
    }
}