<?php
namespace Yolisses\TimeConstraints\Interval;

class TimeInterval
{
    public function __construct(public \DateTime $start, public \DateTime $end)
    {
        if (!($end > $start)) {
            throw new \InvalidArgumentException('End must be greater than start');
        }
    }
}
