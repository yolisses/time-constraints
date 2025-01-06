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

        $a = new SimpleTimeInterval(
            new \DateTime('2021-01-01 00:00:00'),
            new \DateTime('2021-01-01 01:00:00')
        );
        $b = new EmptyTimeInterval();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, $a);
    }

    public function testUnionWithSimpleTimeIntervalIntersecting()
    {
        // A        ████
        // B          ████
        // A ∪ B    ██████

        $a = new SimpleTimeInterval(
            new \DateTime('2021-01-01 00:00:00'),
            new \DateTime('2021-01-01 01:00:00')
        );
        $b = new SimpleTimeInterval(
            new \DateTime('2021-01-01 00:30:00'),
            new \DateTime('2021-01-01 01:30:00')
        );

        $a_union_b = $a->union($b);

        $this->assertEquals(
            $a_union_b,
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

        $a = new SimpleTimeInterval(
            new \DateTime('2021-01-01 00:00:00'),
            new \DateTime('2021-01-01 01:00:00')
        );
        $b = new SimpleTimeInterval(
            new \DateTime('2021-01-01 01:00:00'),
            new \DateTime('2021-01-01 02:00:00')
        );

        $a_union_b = $a->union($b);

        $this->assertEquals(
            $a_union_b,
            new CompositeTimeInterval([$a, $b])
        );
    }
}