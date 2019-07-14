<?php
declare(strict_types = 1);

namespace Discount;

use DataMatrix\DiscountAmountMatrix;
use DiscountSet\DiscountSetInterface;
use DiscountSetContainer\DiscountSetContainerInterface;
use Math\Math;
use Output\OutputItem;
use Price\PriceInterface;

/**
 * Rule#3: Accumulated discounts cannot exceed 10 € in a calendar month. If there are not enough funds to fully cover a discount this calendar month, it should be covered partially.
 */
final class AccumulatedDiscountsCannotExceedTenEuroPerCalendarMonth implements DiscountInterface
{
    private const MAX_PER_CALENDAR_MONTH = 10.0; // float

    public function apply(
        DiscountAmountMatrix $discountAmountMatrix,
        PriceInterface $shipmentPriceService,
        DiscountSetContainerInterface $discountSetContainerService,
        DiscountSetInterface $discountSetService,
        OutputItem $outputItem,
        float $priceBeforeAnyDiscountsOnItem,
        float $priceAfterDiscountsAppliedOnDiscountSetPastItems
    ): float
    {
        $accumulatedDiscountInMonth = $discountAmountMatrix->countDiscountsOfDiscountSetContainerDiscountSetInMonth($discountSetContainerService, $discountSetService, $outputItem->getDateTime());

        if (Math::isAEqualOrLessThanB($accumulatedDiscountInMonth, self::MAX_PER_CALENDAR_MONTH)) {
            return $priceAfterDiscountsAppliedOnDiscountSetPastItems; // All is under the limit, ok
        }

        return $this->getTotalPriceWithDiscountsBackUpToMaxDiscountsLimit($accumulatedDiscountInMonth, $priceAfterDiscountsAppliedOnDiscountSetPastItems);
    }

    private function getTotalPriceWithDiscountsBackUpToMaxDiscountsLimit(float $accumulatedDiscountInMonth, float $priceAfterDiscountsAppliedOnDiscountSetPastItems): float
    {
        $overload = Math::aMinusB($accumulatedDiscountInMonth, self::MAX_PER_CALENDAR_MONTH);
        return Math::aPlusB($priceAfterDiscountsAppliedOnDiscountSetPastItems, $overload);
    }
}
