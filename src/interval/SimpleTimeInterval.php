<?php
namespace Yolisses\TimeConstraints\Interval;

class SimpleTimeInterval
{
    public function __construct(public \DateTime $start, public \DateTime $end)
    {
        if ($start > $end) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }
    }
}
