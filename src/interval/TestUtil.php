<?php
namespace Yolisses\TimeConstraints\Interval;

class TestUtil
{
    static function createSimpleTimeInterval(int $start, int $end)
    {
        return new SimpleTimeInterval(
            new \DateTime("0001-01-$start"),
            new \DateTime("0001-01-$end")
        );
    }

    /**
     * ```
     * A    ██
     * B        ██
     * ```
     */
    static function createABeforeB()
    {
        return [
            self::createSimpleTimeInterval(1, 2),
            self::createSimpleTimeInterval(3, 4),
        ];
    }

    /**
     * ```
     * A        ██
     * B    ██
     * ```
     */
    static function createAAfterB()
    {
        return [
            self::createSimpleTimeInterval(3, 4),
            self::createSimpleTimeInterval(1, 2),
        ];
    }

    /**
     * ```
     * A    ████
     * B      ████
     * ```
     */
    static function createAEndingInB()
    {
        return [
            self::createSimpleTimeInterval(1, 3),
            self::createSimpleTimeInterval(2, 4),
        ];
    }

    /**
     * ```
     * A      ████
     * B    ████
     * ```
     */
    static function createAStartingInB()
    {
        return [
            self::createSimpleTimeInterval(2, 4),
            self::createSimpleTimeInterval(1, 3),
        ];
    }

    /**
     * ```
     * A    ██████
     * B      ██
     * ```
     */
    static function createAContainingB()
    {
        return [
            self::createSimpleTimeInterval(1, 4),
            self::createSimpleTimeInterval(2, 3),
        ];
    }

    /**
     * ```
     * A      ██
     * B    ██████
     * ```
     */
    static function createAContainedByB()
    {
        return [
            self::createSimpleTimeInterval(2, 3),
            self::createSimpleTimeInterval(1, 4),
        ];
    }

    /**
     * ```
     * A    ██████
     * B    ██████
     * ```
     */
    static function createAEqualB()
    {
        return [
            self::createSimpleTimeInterval(1, 4),
            self::createSimpleTimeInterval(1, 4),
        ];
    }
}