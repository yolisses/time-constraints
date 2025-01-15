<?php

namespace Yolisses\TimeConstraints\Interval;

class Edge
{
    public function __construct(public \DateTime $instant, public bool $isStart)
    {
    }
}