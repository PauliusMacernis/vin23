<?php

namespace Math;

class Math
{
    /**
     * There are many ways on how to check on one float (A) being less/equal/greater than another float (B)
     * This is one of the cleanest good ways.
     * Another ways may be by using any/all of the following: BCMath, PHP_FLOAT_EPSILON, etc.
     */
    public static function isALessThanB(float $a, float $b): bool
    {
        return round($a, APPLICATION_DECIMAL_PRECISION) < round($b, APPLICATION_DECIMAL_PRECISION);
    }

    public static function aMinusB(float $a, float $b): float
    {
        return round($a, APPLICATION_DECIMAL_PRECISION) - round($b, APPLICATION_DECIMAL_PRECISION);
    }
}
