<?php

namespace Discount;

use Carrier\CarrierFrance;
use Input\InputItem;
use Package\Package;
use Price\ShipmentPriceInterface;

/**
 * Rule#2: Third L shipment via LP should be free, but only once a calendar month.
 */
class ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth implements DiscountInterface
{
    public function apply(ShipmentPriceInterface $shipmentPriceService, float $priceWithoutDiscount, InputItem $inputItem): float
    {
        if ($inputItem->getPackageSizeCode() !== Package::ITEMS['L']['code']) {
            return $priceWithoutDiscount;
        }

        if ($inputItem->getCarrierCode() !== CarrierFrance::ITEMS['LP']['code']) {
            return $priceWithoutDiscount;
        }

        $lShipmentViaLpOnInputItemMonth = $inputItem->getTransactionsCountMatrix()->countItemsOfSizeOfCarrierInMonth(
            $inputItem->getPackageSizeCode(),
            $inputItem->getCarrierCode(),
            $inputItem->getDateTime()
        );

        if ($lShipmentViaLpOnInputItemMonth === 3) { // Yep, this is it! Apply the discount.
            return round(0, APPLICATION_DECIMAL_PRECISION);
        }

        return $priceWithoutDiscount;
    }
}
