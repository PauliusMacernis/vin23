<?php

namespace Discount;

use Input\InputItem;
use Price\ShipmentPriceInterface;

/**
 * This interface is used to enforce all appliable discounts to be certain type of classes only.
 */
interface DiscountInterface
{
    public function apply(ShipmentPriceInterface $shipmentPriceService, float $priceWithoutDiscount, InputItem $inputItem): float;
}
