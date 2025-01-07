<?php
namespace Yolisses\TimeConstraints\Interval;

use PHPUnit\Framework\TestCase;

class SimpleTimeIntervalUnionTest extends TestCase
{
    public function testWithABeforeB()
    {
        // A        ██
        // B            ██
        // A ∪ B    ██  ██

        [$a, $b] = TestUtil::createABeforeB();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, new CompositeTimeInterval([$a, $b]));
    }

    public function testWithAAfterB()
    {
        // A            ██
        // B        ██
        // A ∪ B    ██  ██

        [$a, $b] = TestUtil::createAAfterB();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, new CompositeTimeInterval([$a, $b]));
    }

    public function testWithAEngingInB()
    {
        // A        ████
        // B          ████
        // A ∪ B    ██████

        [$a, $b] = TestUtil::createAEndingInB();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, TestUtil::createSimpleTimeInterval(1, 4));
    }

    public function testWithAStartingInB()
    {
        // A          ████
        // B        ████
        // A ∪ B    ██████

        [$a, $b] = TestUtil::createAStartingInB();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, TestUtil::createSimpleTimeInterval(1, 4));
    }

    public function testWithAContainingB()
    {
        // A        ██████
        // B          ██
        // A ∪ B    ██████

        [$a, $b] = TestUtil::createAContainingB();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, TestUtil::createSimpleTimeInterval(1, 4));
    }

    public function testWithAContainedByB()
    {
        // A          ██
        // B        ██████
        // A ∪ B    ██████

        [$a, $b] = TestUtil::createAContainedByB();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, $b);
    }

    public function testWithAEqualB()
    {
        // A        ██████
        // B        ██████
        // A ∪ B    ██████

        [$a, $b] = TestUtil::createAEqualB();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, $a);
    }
}