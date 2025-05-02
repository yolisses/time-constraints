<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class AfterTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $instant)
    {
    }

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        //     s   e   
        //           i
        //           ██
        if ($this->instant > $end_instant) {
            return [];
        }

        //     s   e   
        //       i
        //       ██████

        //     s   e   
        //   i
        //   ██████████
        $intervals = [new TimeInterval($this->instant, $end_instant)];
        return $this->clampIntervals($intervals, $start_instant, $end_instant);
    }
}