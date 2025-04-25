<?php

namespace Yolisses\TimeConstraints\Constraint;

use Yolisses\TimeConstraints\Interval\TimeInterval;

class BeforeTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $instant)
    {
    }

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        //     s   e   
        //   i
        // ██
        if ($this->instant < $start_instant) {
            return [];
        }

        //     s   e   
        //       i
        // ██████

        //     s   e   
        //           i
        // ██████████
        $intervals = [new TimeInterval($start_instant, $this->instant)];
        return $this->clampIntervals($intervals, $start_instant, $end_instant);
    }
}