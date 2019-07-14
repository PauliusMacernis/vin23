<?php
// @TODO: stricttypes=1 header
namespace Price;

interface PriceInterface
{
    public function getShipmentPrice(string $carrierCode, string $packageSizeCode): float;

    public function getAllPrices(): array;
}
