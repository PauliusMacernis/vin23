<?php
declare(strict_types = 1);

namespace Price;

use Exception\IgnorableItemException;

abstract class Price implements PriceInterface
{
    protected const PRICE_TABLE_EUR = [];

    public function getShipmentPrice(string $carrierCode, string $packageSizeCode): float
    {
        if (!isset(static::PRICE_TABLE_EUR[$carrierCode])) {
            throw new IgnorableItemException(sprintf(
                'There is no such carrier in France: %s',
                $carrierCode
            ));
        }

        if (!isset(static::PRICE_TABLE_EUR[$carrierCode][$packageSizeCode])) {
            throw new IgnorableItemException(sprintf(
                'Carrier %s does not offer such package size carrying in France: %s',
                $carrierCode,
                $packageSizeCode
            ));
        }

        return static::PRICE_TABLE_EUR[$carrierCode][$packageSizeCode];
    }

    public function getAllPrices(): array
    {
        return static::PRICE_TABLE_EUR;
    }
}
