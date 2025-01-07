<?php
namespace Yolisses\TimeConstraints\Interval;

use Exception;

class MockTimeInterval implements TimeInterval
{
    function union(TimeInterval $time_interval): TimeInterval
    {
        throw new Exception("Not implemented");
    }

    function intersection(TimeInterval $time_interval): TimeInterval
    {
        throw new Exception("Not implemented");
    }

    function difference(TimeInterval $time_interval): TimeInterval
    {
        throw new Exception("Not implemented");
    }
}