<?php
namespace Yolisses\TimeConstraints;

class TestUtil
{
    static function createSimpleTimeInterval(int $start, int $end)
    {
        return new SimpleTimeInterval(
            new \DateTime("0001-01-$start"),
            new \DateTime("0001-01-$end")
        );
    }
}