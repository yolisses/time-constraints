<?php

use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    function testAddSeconds()
    {
        $date = new DateTimeImmutable('2025-01-01 00:00');

        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:00:01'), $date->modify('1 second'));
        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:01:00'), $date->modify('60 second'));
        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:00:01'), $date->modify('1 seconds'));
        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:01:00'), $date->modify('60 seconds'));

        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:00:01'), $date->modify('+1 second'));
        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:01:00'), $date->modify('+60 second'));
        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:00:01'), $date->modify('+1 seconds'));
        $this->assertEquals(new DateTimeImmutable('2025-01-01 00:01:00'), $date->modify('+60 seconds'));

        $this->assertEquals(new DateTimeImmutable('2024-12-31 23:59:59'), $date->modify('-1 second'));
        $this->assertEquals(new DateTimeImmutable('2024-12-31 23:59:00'), $date->modify('-60 second'));
        $this->assertEquals(new DateTimeImmutable('2024-12-31 23:59:59'), $date->modify('-1 seconds'));
        $this->assertEquals(new DateTimeImmutable('2024-12-31 23:59:00'), $date->modify('-60 seconds'));

        // Unexpected behavior
        $this->assertNotEquals(new DateTimeImmutable('2024-12-31 23:59:59'), $date->modify('+-1 second'));
        $this->assertNotEquals(new DateTimeImmutable('2024-12-31 23:59:00'), $date->modify('+-60 second'));
        $this->assertNotEquals(new DateTimeImmutable('2024-12-31 23:59:59'), $date->modify('+-1 seconds'));
        $this->assertNotEquals(new DateTimeImmutable('2024-12-31 23:59:00'), $date->modify('+-60 seconds'));

        // Conclusion: don't use multiple signs
    }
}