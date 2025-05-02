<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;
use Yolisses\TimeConstraints\Period\TimePeriod;

class AfterTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $instant)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
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
        return $this->clampSequence($sequence, $start_instant, $end_instant);
    }
}