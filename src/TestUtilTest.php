<?php
namespace Yolisses\TimeConstraints;

use PHPUnit\Framework\TestCase;

class TestUtilTest extends TestCase
{
    public function testCreateSimpleTimeInterval()
    {
        $start = 1;
        $end = 2;
        $simpleTimeInterval = TestUtil::createSimpleTimeInterval($start, $end);
        $this->assertEquals(
            $simpleTimeInterval,
            new SimpleTimeInterval(
                new \DateTime("0001-01-01"),
                new \DateTime("0001-01-02")
            )
        );
    }
}