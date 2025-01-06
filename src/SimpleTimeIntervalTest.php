<?php

namespace Yolisses\TimeConstraints;

use PHPUnit\Framework\TestCase;

class SimpleTimeIntervalTest extends TestCase
{
    public function testUnionWithEmptyTimeInterval()
    {
        // A        ██████
        // B        
        // A ∪ B    ██████

        $simple_time_interval = new SimpleTimeInterval(
            new \DateTime('2021-01-01 00:00:00'),
            new \DateTime('2021-01-01 01:00:00')
        );
        $empty_time_interval = new EmptyTimeInterval();

        $union_time_interval = $simple_time_interval->union($empty_time_interval);

        $this->assertEquals($union_time_interval, $simple_time_interval);
    }

    public function testUnionWithSimpleTimeIntervalIntersecting()
    {
        // A        ████
        // B          ████
        // A ∪ B    ██████

        $simple_time_interval = new SimpleTimeInterval(
            new \DateTime('2021-01-01 00:00:00'),
            new \DateTime('2021-01-01 01:00:00')
        );
        $simple_time_interval_2 = new SimpleTimeInterval(
            new \DateTime('2021-01-01 00:30:00'),
            new \DateTime('2021-01-01 01:30:00')
        );

        $union_time_interval = $simple_time_interval->union($simple_time_interval_2);

        $this->assertEquals(
            $union_time_interval,
            new SimpleTimeInterval(
                new \DateTime('2021-01-01 00:00:00'),
                new \DateTime('2021-01-01 01:30:00')
            )
        );
    }

    public function testUnionWithSimpleTimeIntervalNotIntersecting()
    {
        // A        ██
        // B            ██
        // A ∪ B    ██  ██

        $simple_time_interval = new SimpleTimeInterval(
            new \DateTime('2021-01-01 00:00:00'),
            new \DateTime('2021-01-01 01:00:00')
        );
        $simple_time_interval_2 = new SimpleTimeInterval(
            new \DateTime('2021-01-01 01:00:00'),
            new \DateTime('2021-01-01 02:00:00')
        );

        $union_time_interval = $simple_time_interval->union($simple_time_interval_2);

        $this->assertEquals(
            $union_time_interval,
            new CompositeTimeInterval([$simple_time_interval, $simple_time_interval_2])
        );
    }
}