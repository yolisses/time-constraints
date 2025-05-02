<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Period\TimePeriod;

class BeforeTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $instant)
    {
    }

    public function getPeriods(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
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
        $periods = [new TimePeriod($start_instant, $this->instant)];
        return $this->clampPeriods($periods, $start_instant, $end_instant);
    }
}