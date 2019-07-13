<?php
// @TODO: stricttypes=1 header
namespace Price;

use Input\InputItem;

interface ShipmentPriceInterface
{
    public function getShipmentPrice(string $carrierCode, string $packageSizeCode): float;
    public function getShipmentPriceWithDiscounts(float $priceWithoutDiscount, InputItem $input): float;
    public function getAllPrices(): array;
}
