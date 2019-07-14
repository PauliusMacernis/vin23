<?php
declare(strict_types = 1);

namespace Price;

interface PriceInterface
{
    public function getShipmentPrice(string $carrierCode, string $packageSizeCode): float;

    public function getAllPrices(): array;
}
