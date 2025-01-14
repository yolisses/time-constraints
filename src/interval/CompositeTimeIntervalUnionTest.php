<?php

use PHPUnit\Framework\TestCase;
use Yolisses\TimeConstraints\Interval\CompositeTimeInterval;
use Yolisses\TimeConstraints\Interval\EmptyTimeInterval;
use Yolisses\TimeConstraints\Interval\TestUtil;

// TODO find better test names
class CompositeTimeIntervalUnionTest extends TestCase
{
    public function testWithEmptyAAndEmptyB1()
    {
        // A
        // B        
        // A ∪ B

        $a = new CompositeTimeInterval();
        $b = new CompositeTimeInterval();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, new EmptyTimeInterval);
    }

    public function testWithEmptyAAndEmptyB2()
    {
        // A
        // B        
        // A ∪ B

        $a = new CompositeTimeInterval();
        $b = new EmptyTimeInterval();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, new EmptyTimeInterval);
    }

    public function testWithEmptyAAndSimpleB()
    {
        // A        
        // B        ██
        // A ∪ B    ██

        $a = new CompositeTimeInterval();
        $b = TestUtil::createSimpleTimeInterval(1, 2);

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, TestUtil::createSimpleTimeInterval(1, 2));
    }


    public function testWithFilledAAndSimpleB()
    {
        // A        ██
        // B            ██
        // A ∪ B    ██  ██

        $a = new CompositeTimeInterval([TestUtil::createSimpleTimeInterval(1, 2)]);
        $b = TestUtil::createSimpleTimeInterval(3, 4);

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, new CompositeTimeInterval([
            TestUtil::createSimpleTimeInterval(1, 2),
            TestUtil::createSimpleTimeInterval(3, 4),
        ]));
    }

    public function testWithFilledAAndSimpleBOverlapping()
    {
        // A        ████
        // B          ████
        // A ∪ B    ██████

        $a = new CompositeTimeInterval([
            TestUtil::createSimpleTimeInterval(1, 3)
        ]);
        $b = TestUtil::createSimpleTimeInterval(2, 4);

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, TestUtil::createSimpleTimeInterval(1, 4));
    }

    public function testWithFilledAAndFilledB1()
    {
        // A        ██
        // B            ██
        // A ∪ B    ██  ██

        $a = new CompositeTimeInterval([
            TestUtil::createSimpleTimeInterval(1, 2),
        ]);
        $b = new CompositeTimeInterval([
            TestUtil::createSimpleTimeInterval(3, 4),
        ]);

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, new CompositeTimeInterval([
            TestUtil::createSimpleTimeInterval(1, 2),
            TestUtil::createSimpleTimeInterval(3, 4),
        ]));
    }

    public function testWithFilledAAndFilledB2()
    {
        // A        ██
        // B          ██
        // A ∪ B    ████

        $a = new CompositeTimeInterval([
            TestUtil::createSimpleTimeInterval(1, 2),
        ]);
        $b = new CompositeTimeInterval([
            TestUtil::createSimpleTimeInterval(2, 3),
        ]);

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, TestUtil::createSimpleTimeInterval(1, 3));
    }

    public function testWithFilledAAndFilledB3()
    {
        // A        ██  ██
        // B                  ██
        // A ∪ B    ██  ██    ██

        $a = new CompositeTimeInterval([
            TestUtil::createSimpleTimeInterval(1, 2),
            TestUtil::createSimpleTimeInterval(3, 4),
        ]);
        $b = new CompositeTimeInterval([
            // TestUtil::createSimpleTimeInterval(4, 5),
            TestUtil::createSimpleTimeInterval(6, 7),
        ]);

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, new CompositeTimeInterval([
            TestUtil::createSimpleTimeInterval(1, 2),
            TestUtil::createSimpleTimeInterval(3, 5),
            TestUtil::createSimpleTimeInterval(6, 7),
        ]));
    }
}