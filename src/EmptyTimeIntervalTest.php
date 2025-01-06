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

        $empty_time_interval = new EmptyTimeInterval;
        $other_time_interval = new MockTimeInterval();

        $union_time_interval = $empty_time_interval->union($other_time_interval);

        $this->assertEquals($union_time_interval, $other_time_interval);
    }

    public function testIntersection()
    {
        // A        
        // B        ░░░░░░
        // A ∪ B    

        $empty_time_interval = new EmptyTimeInterval;
        $other_time_interval = new MockTimeInterval();

        $intersection_time_interval = $empty_time_interval->intersection($other_time_interval);

        $this->assertEquals($intersection_time_interval, new EmptyTimeInterval);
    }

    public function testDifference()
    {
        // A        
        // B        ░░░░░░
        // A ∪ B    

        $empty_time_interval = new EmptyTimeInterval;
        $other_time_interval = new MockTimeInterval();

        $difference_time_interval = $empty_time_interval->difference($other_time_interval);

        $this->assertEquals($difference_time_interval, new EmptyTimeInterval);
    }
}