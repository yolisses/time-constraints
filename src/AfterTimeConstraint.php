<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Period\TimePeriod;

class AfterTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $instant)
    {
    }

    public function getPeriods(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
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
        $periods = [new TimePeriod($this->instant, $end_instant)];
        return $this->clampPeriods($periods, $start_instant, $end_instant);
    }
}