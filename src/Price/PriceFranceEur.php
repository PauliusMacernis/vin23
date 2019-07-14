<?php
declare(strict_types = 1);

namespace Price;

/**
 * Deals with the information on carrier prices in France
 */
class PriceFranceEur extends Price
{
    protected const PRICE_TABLE_EUR = [
        'LP' => [
            'S' => 1.50,
            'M' => 4.90,
            'L' => 6.90,
        ],
        'MR' => [
            'S' => 2.00,
            'M' => 3.00,
            'L' => 4.00,
        ],
    ];
}
