<?php
declare(strict_types = 1);

namespace Math;

/**
 * Contains methods used in float numbers manipulation, e.g. add/subtract/compare/etc.
 * There are many ways on how to perform actions on float-type data, this class uses one of all available.
 * Another ways to go with the math would be by using any/all of the following: BCMath, PHP_FLOAT_EPSILON, etc.
 * You may easily change your "math logic" and plug it into application if you want to...
 */
class Math
{
    public static function isALessThanB(float $a, float $b): bool
    {
        return round($a, APPLICATION_DECIMAL_PRECISION) < round($b, APPLICATION_DECIMAL_PRECISION);
    }

    public static function isAEqualB(float $a, float $b): bool
    {
        return round($a, APPLICATION_DECIMAL_PRECISION) === round($b, APPLICATION_DECIMAL_PRECISION);
    }

    public static function isAEqualOrLessThanB(float $a, float $b): bool
    {
        return round($a, APPLICATION_DECIMAL_PRECISION) <= round($b, APPLICATION_DECIMAL_PRECISION);
    }

    public static function aMinusB(float $a, float $b): float
    {
        return round($a, APPLICATION_DECIMAL_PRECISION) - round($b, APPLICATION_DECIMAL_PRECISION);
    }

    // @TODO: Any other place with adding the numbers? This way we may refactor that place with this method.
    public static function aPlusB(float $a, float $b): float
    {
        return round($a, APPLICATION_DECIMAL_PRECISION) + round($b, APPLICATION_DECIMAL_PRECISION);
    }

    public static function getNumber(float $number): float
    {
        return round($number, APPLICATION_DECIMAL_PRECISION);
    }
}
