<?php
namespace Yolisses\TimeConstraints\Interval;

use PHPUnit\Framework\TestCase;

class TestUtilTest extends TestCase
{
    public function testCreateTimeInterval()
    {
        $this->assertEquals(
            new TimeInterval(
                new \DateTime("0001-01-01"),
                new \DateTime("0001-01-02")
            ),
            TestUtil::createTimeInterval(1, 2),
        );
    }
}