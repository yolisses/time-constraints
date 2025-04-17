<?php

use Yolisses\TimeConstraints\Interval\Edge;

function createEdge(int $instant, bool $is_start, bool $is_included)
{
    return new Edge(createInstant($instant), $is_start, $is_included);
}