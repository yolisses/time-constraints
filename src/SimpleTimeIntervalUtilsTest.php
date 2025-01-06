<?php
namespace Yolisses\TimeConstraints;

use PHPUnit\Framework\TestCase;

class SimpleTimeIntervalUtilsTest extends TestCase
{
    public function testGetIsBefore()
    {
        [$a, $b] = TestUtil::createABeforeB();
        $this->assertEquals($a->getIsBefore($b), true);

        [$a, $b] = TestUtil::createAAfterB();
        $this->assertEquals($a->getIsBefore($b), false);
    }

    public function testGetIsAfter()
    {
        [$a, $b] = TestUtil::createAAfterB();
        $this->assertEquals($a->getIsAfter($b), true);

        [$a, $b] = TestUtil::createABeforeB();
        $this->assertEquals($a->getIsAfter($b), false);
    }

    public function testGetIsEqual()
    {
        [$a, $b] = TestUtil::createAEqualB();
        $this->assertEquals($a->getIsEqual($b), true);

        [$a, $b] = TestUtil::createABeforeB();
        $this->assertEquals($a->getIsEqual($b), false);
    }
}