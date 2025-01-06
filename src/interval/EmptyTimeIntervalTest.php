<?php
namespace Yolisses\TimeConstraints;

use PHPUnit\Framework\TestCase;

class EmptyTimeIntervalTest extends TestCase
{
    public function testUnion()
    {
        // A        
        // B        ░░░░░░
        // A ∪ B    ░░░░░░

        $a = new EmptyTimeInterval;
        $b = new MockTimeInterval();

        $a_union_b = $a->union($b);

        $this->assertEquals($a_union_b, $b);
    }

    public function testIntersection()
    {
        // A        
        // B        ░░░░░░
        // A ∪ B    

        $a = new EmptyTimeInterval;
        $b = new MockTimeInterval();

        $a_intersection_b = $a->intersection($b);

        $this->assertEquals($a_intersection_b, new EmptyTimeInterval);
    }

    public function testDifference()
    {
        // A        
        // B        ░░░░░░
        // A ∪ B    

        $a = new EmptyTimeInterval;
        $b = new MockTimeInterval();

        $a_difference_b = $a->difference($b);

        $this->assertEquals($a_difference_b, new EmptyTimeInterval);
    }
}