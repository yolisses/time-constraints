<?php
namespace Yolisses\TimeConstraints\Interval;

use PHPUnit\Framework\TestCase;

class SimpleTimeIntervalIntersectionTest extends TestCase
{

    public function testWithABeforeB()
    {
        // A        ██
        // B            ██
        // A ∩ B    

        [$a, $b] = TestUtil::createABeforeB();

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals($a_intersection_b, new EmptyTimeInterval());
    }

    public function testWithAAfterB()
    {
        // A            ██
        // B        ██
        // A ∩ B    

        [$a, $b] = TestUtil::createAAfterB();

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals($a_intersection_b, new EmptyTimeInterval());
    }

    public function testWithAEngingInB()
    {
        // A        ████
        // B          ████
        // A ∩ B      ██

        [$a, $b] = TestUtil::createAEndingInB();

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals($a_intersection_b, TestUtil::createSimpleTimeInterval(2, 3));
    }

    public function testWithAStartingInB()
    {
        // A          ████
        // B        ████
        // A ∩ B      ██

        [$a, $b] = TestUtil::createAStartingInB();

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals($a_intersection_b, TestUtil::createSimpleTimeInterval(2, 3));
    }

    public function testWithAContainingB()
    {
        // A        ██████
        // B          ██
        // A ∩ B      ██

        [$a, $b] = TestUtil::createAContainingB();

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals($a_intersection_b, TestUtil::createSimpleTimeInterval(2, 3));
    }

    public function testWithAContainedByB()
    {
        // A          ██
        // B        ██████
        // A ∩ B      ██

        [$a, $b] = TestUtil::createAContainedByB();

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals($a_intersection_b, TestUtil::createSimpleTimeInterval(2, 3));
    }

    public function testWithAEqualB()
    {
        // A        ██████
        // B        ██████
        // A ∩ B    ██████

        [$a, $b] = TestUtil::createAEqualB();

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals($a_intersection_b, TestUtil::createSimpleTimeInterval(1, 4));
    }
}