<?php

namespace Yolisses\TimeConstraints;

use PHPUnit\Framework\TestCase;

class SimpleTimeIntervalIntersectionTest extends TestCase
{
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

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals(
            $a_intersection_b,
            new SimpleTimeInterval(
                new \DateTime('0001-01-02'),
                new \DateTime('0001-01-03')
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

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals(
            $a_intersection_b,
            new EmptyTimeInterval()
        );
    }
}