<?php

namespace Yolisses\TimeConstraints;

use League\Period\Period;
use League\Period\Sequence;

class BeforeTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $instant)
    {
    }

    public function getSequence(Period $clampPeriod): Sequence
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
        return $this->clampSequence($sequence, $clampPeriod);
    }
}