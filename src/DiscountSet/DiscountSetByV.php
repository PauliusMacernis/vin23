<?php
declare(strict_types = 1);

namespace DiscountSet;

use DataMatrix\DiscountAmountMatrix;
use Discount\AccumulatedDiscountsCannotExceedTenEuroPerCalendarMonth;
use Discount\AllSShipmentsAlwaysMatchLowestSPackagePriceAmongProviders;
use Discount\ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth;
use DiscountSetContainer\DiscountSetContainerInterface;
use Output\OutputItem;
use Price\PriceInterface;

class DiscountSetByV extends DiscountSet
{
    public function getPriceWithDiscountsApplied(PriceInterface $shipmentPriceService, DiscountSetContainerInterface $discountSetContainerService, float $priceWithoutDiscount, OutputItem $output, DiscountAmountMatrix $discountAmountMatrix): float
    {
        // Rule#1: All S shipments should always match the lowest S package price among the providers.
        $price = $this->applyDiscount(AllSShipmentsAlwaysMatchLowestSPackagePriceAmongProviders::class, $discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $this, $priceWithoutDiscount, $priceWithoutDiscount, $output);

        // Rule#2: Third L shipment via LP should be free, but only once a calendar month.
        $price = $this->applyDiscount(ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth::class, $discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $this, $priceWithoutDiscount, $price, $output);

        // Rule#3: Accumulated discounts cannot exceed 10 € in a calendar month. If there are not enough funds to fully cover a discount this calendar month, it should be covered partially.
        $price = $this->applyDiscount(AccumulatedDiscountsCannotExceedTenEuroPerCalendarMonth::class, $discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $this, $priceWithoutDiscount, $price, $output);

        return $price;
    }
}
