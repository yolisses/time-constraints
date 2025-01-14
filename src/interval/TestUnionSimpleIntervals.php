<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\SimpleTimeInterval;

class TestUnionSimpleIntervals extends TestCase
{
    function testUnionSimpleIntervals()
    {
        $intervals = [
            new SimpleTimeInterval(new DateTime('2021-01-01 00:00:00'), new DateTime('2021-01-01 01:00:00')),
            new SimpleTimeInterval(new DateTime('2021-01-01 00:30:00'), new DateTime('2021-01-01 01:30:00')),
            new SimpleTimeInterval(new DateTime('2021-01-01 01:00:00'), new DateTime('2021-01-01 02:00:00')),
            new SimpleTimeInterval(new DateTime('2021-01-01 01:30:00'), new DateTime('2021-01-01 02:30:00')),
        ];

        $result = unionSimpleTimeIntervals($intervals);

        $this->assertEquals(
            [
                new SimpleTimeInterval(new DateTime('2021-01-01 00:00:00'), new DateTime('2021-01-01 02:00:00')),
                new SimpleTimeInterval(new DateTime('2021-01-01 01:30:00'), new DateTime('2021-01-01 02:30:00')),
            ],
            $result
        );
    }
}