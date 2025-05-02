<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\Period\TimePeriod;

class BeforeTimeConstraint extends TimeConstraint
{
    public function __construct(public \DateTimeImmutable $instant)
    {
    }

    public function getSequence(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): Sequence
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
        return $this->clampSequence($sequence, $start_instant, $end_instant);
    }
}