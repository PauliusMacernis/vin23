<?php

namespace Price;

use Discount\AllSShipmentsAlwaysMatchLowestSPackagePriceAmongProviders;
use Discount\DiscountInterface;
use Discount\ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth;
use Input\InputItem;
use RuntimeException;

trait ShipmentDiscountSetByV
{
    public function getShipmentPriceWithDiscounts(float $priceWithoutDiscount, InputItem $input): float
    {
        // Rule#1: All S shipments should always match the lowest S package price among the providers.
        $price = $this->applyDiscount(AllSShipmentsAlwaysMatchLowestSPackagePriceAmongProviders::class, $this, $priceWithoutDiscount, $input);

        // Rule#2: Third L shipment via LP should be free, but only once a calendar month.
        $price = $this->applyDiscount(ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth::class, $this, $price, $input);

        // @TODO: Rule#3: Accumulated discounts cannot exceed 10 € in a calendar month. If there are not enough funds to fully cover a discount this calendar month, it should be covered partially.

        return $price;
    }

    private function applyDiscount(string $className): float
    {
        if (!class_exists($className)) {
           throw new RuntimeException(sprintf('Discount class %s does not exist.', $className));
        }

        $discountObject = new $className;
        if (!$discountObject instanceof DiscountInterface) {
            throw new RuntimeException(sprintf('All discount classes must implement common interface: %s', DiscountInterface::class));
        }

        $arguments = func_get_args();
        array_shift($arguments);

        return $discountObject->apply($arguments[0], $arguments[1], $arguments[2]);
    }
}
