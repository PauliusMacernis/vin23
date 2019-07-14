<?php
declare(strict_types = 1);

namespace Discount;

use Carrier\CarrierFrance;
use DataMatrix\DiscountAmountMatrix;
use DiscountSet\DiscountSetInterface;
use DiscountSetContainer\DiscountSetContainerInterface;
use Input\InputItem;
use Math\Math;
use Package\Package;
use Price\PriceInterface;

/**
 * Rule#2: Third L shipment via LP should be free, but only once a calendar month.
 */
final class ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth implements DiscountInterface
{
    private const FREE_ITEM_NUMBER_FOR_L_VIA_LP_IN_CALENDAR_MONTH = 3;

    public function apply(
        DiscountAmountMatrix $discountAmountMatrix,
        PriceInterface $shipmentPriceService,
        DiscountSetContainerInterface $discountSetContainerService,
        DiscountSetInterface $discountSetService,
        InputItem $outputItem,
        float $priceBeforeAnyDiscountsOnItem,
        float $priceAfterDiscountsAppliedOnDiscountSetPastItems
    ): float
    {
        if ($outputItem->getPackageSizeCode() !== Package::ITEMS['L']['code']) {
            return $priceAfterDiscountsAppliedOnDiscountSetPastItems;
        }

        if ($outputItem->getCarrierCode() !== CarrierFrance::ITEMS['LP']['code']) {
            return $priceAfterDiscountsAppliedOnDiscountSetPastItems;
        }

        $lShipmentViaLpOnInputItemMonth = $outputItem->getTransactionsCountMatrix()->countItemsOfSizeOfCarrierInMonth(
            $outputItem->getPackageSizeCode(),
            $outputItem->getCarrierCode(),
            $outputItem->getDateTime()
        );

        if ($lShipmentViaLpOnInputItemMonth !== self::FREE_ITEM_NUMBER_FOR_L_VIA_LP_IN_CALENDAR_MONTH) {
            return $priceAfterDiscountsAppliedOnDiscountSetPastItems;
        }

        return Math::getNumber(0.0); // Yep, this is it! Apply the discount.
    }
}
