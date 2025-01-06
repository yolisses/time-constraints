<?php

namespace Yolisses\TimeConstraints;

use PHPUnit\Framework\TestCase;

class SimpleTimeIntervalUnionTest extends TestCase
{
    public function testWithIntersection()
    {
        // A        ████
        // B          ████
        // A ∪ B    ██████

        $a = TestUtil::createSimpleTimeInterval(1, 3);
        $b = TestUtil::createSimpleTimeInterval(2, 4);

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, TestUtil::createSimpleTimeInterval(1, 4));
    }

    public function testWithoutIntersection()
    {
        // A        ██
        // B            ██
        // A ∪ B    ██  ██

        $a = TestUtil::createSimpleTimeInterval(1, 2);
        $b = TestUtil::createSimpleTimeInterval(3, 4);

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, new CompositeTimeInterval([$a, $b]));
    }
}