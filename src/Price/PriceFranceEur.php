<?php

namespace Price;

use Exception\IgnorableItemException;

final class PriceFranceEur implements PriceInterface
{
    // @TODO: DB + ORM + Repository pattern + Separate objects for carrier, package
    // @TODO: Connect this with Carrier and Package objects information
    private const PRICE_TABLE_EUR = [
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

    public function getShipmentPrice(string $carrierCode, string $packageSizeCode): float
    {
        if (!isset(self::PRICE_TABLE_EUR[$carrierCode])) {
            // @TODO: Consider developing application-specific custom exception class extending from SPL exception
            throw new IgnorableItemException(sprintf(
                'There is no such carrier in France: %s',
                $carrierCode
            ));
        }

        if (!isset(self::PRICE_TABLE_EUR[$carrierCode][$packageSizeCode])) {
            throw new IgnorableItemException(sprintf(
                'Carrier %s does not offer such package size carrying in France: %s',
                $carrierCode,
                $packageSizeCode
            ));
        }

        return self::PRICE_TABLE_EUR[$carrierCode][$packageSizeCode];
    }

    public function getAllPrices(): array
    {
        return self::PRICE_TABLE_EUR;
    }
}
