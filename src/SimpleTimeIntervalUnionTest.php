<?php

namespace Yolisses\TimeConstraints;

use PHPUnit\Framework\TestCase;

class SimpleTimeIntervalUnionTest extends TestCase
{
    public function testWithEmptyTimeInterval()
    {
        // A        ██████
        // B        
        // A ∪ B    ██████

        $a = new SimpleTimeInterval(
            new \DateTime('0001-01-01'),
            new \DateTime('0001-01-04'),
        );
        $b = new EmptyTimeInterval();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, $a);
    }

    public function testWithIntersection()
    {
        // A        ████
        // B          ████
        // A ∪ B    ██████

        $a = new SimpleTimeInterval(
            new \DateTime('0001-01-01'),
            new \DateTime('0001-01-03'),
        );
        $b = new SimpleTimeInterval(
            new \DateTime('0001-01-02'),
            new \DateTime('0001-01-04'),
        );

        $a_union_b = $a->union($b);

        $this->assertEquals(
            $a_union_b,
            new SimpleTimeInterval(
                new \DateTime('0001-01-01'),
                new \DateTime('0001-01-04')
            )
        );
    }

    public function testWithoutIntersection()
    {
        // A        ██
        // B            ██
        // A ∪ B    ██  ██

        $a = new SimpleTimeInterval(
            new \DateTime('0001-01-01'),
            new \DateTime('0001-01-02'),
        );
        $b = new SimpleTimeInterval(
            new \DateTime('0001-01-03'),
            new \DateTime('0001-01-04'),
        );

        $a_union_b = $a->union($b);

        $this->assertEquals(
            $a_union_b,
            new CompositeTimeInterval([$a, $b])
        );
    }
}