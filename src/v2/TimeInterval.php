<?php

namespace Yolisses\TimeConstraints\V2;


class TimeInterval
{
    public $start;
    public $end;
    public $start_is_included;
    public $end_is_included;

    public function __construct(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
        bool $start_is_included = true,
        bool
        $end_is_included = true
    ) {
        if ($start > $end || ($start == $end && !($start_is_included && $end_is_included))) {
            throw new \InvalidArgumentException("Invalid interval: start must be less than or equal to end");
        }
        $this->start = $start;
        $this->end = $end;
        $this->start_is_included = $start_is_included;
        $this->end_is_included = $end_is_included;
    }
}