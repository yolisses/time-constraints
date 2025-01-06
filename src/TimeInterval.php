<?php
namespace Yolisses\TimeConstraints;

interface TimeInterval
{
    function union(TimeInterval $time_interval): TimeInterval;
    function intersection(TimeInterval $time_interval): TimeInterval;
    function difference(TimeInterval $time_interval): TimeInterval;
}
