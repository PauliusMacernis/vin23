<?php

namespace Discount;

use Input\InputItem;
use Math\Math;
use Package\Package;
use Price\ShipmentPriceInterface;
use RuntimeException;


/**
 * Rule#1: All S shipments should always match the lowest S package price among the providers.
 */
class AllSShipmentsAlwaysMatchLowestSPackagePriceAmongProviders implements DiscountInterface
{
    public function apply(ShipmentPriceInterface $shipmentPriceService, float $priceWithoutDiscount, InputItem $inputItem): float
    {
        // All packages, except S
        if ($inputItem->getPackageSizeCode() !== Package::ITEMS['S']['code']) {
            return $priceWithoutDiscount;
        }

        // S packages
        return $this->getLowestSShipmentsPriceAmongProviders($shipmentPriceService->getAllPrices());
    }

    private function getLowestSShipmentsPriceAmongProviders(array $prices): float
    {
        $lowestPrice = null;

        foreach ($prices as $carrierCode => $packages) {

            if (array_key_exists(Package::ITEMS['S']['code'], $packages) === false) {
                throw new RuntimeException(sprintf('Missing package S price: %s', get_class($this)));
            }

            if (null === $lowestPrice) {
                $lowestPrice = $packages[Package::ITEMS['S']['code']];
            }

            if (Math::isALessThanB($packages[Package::ITEMS['S']['code']], $lowestPrice)) {
                $lowestPrice = $packages[Package::ITEMS['S']['code']];
            }
        }

        if ($lowestPrice === null) {
            throw new RuntimeException(sprintf('It is not possible to determine the lowest price: %s', get_class($this)));
        }

        return $lowestPrice;
    }
}
