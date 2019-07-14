<?php

namespace Discount;

use DataMatrix\DiscountAmountMatrix;
use DiscountSet\DiscountSetInterface;
use DiscountSetContainer\DiscountSetContainerInterface;
use Input\InputItem;
use Price\PriceInterface;

/**
 * This interface is used to enforce all appliable discounts to be certain type of classes only.
 */
interface DiscountInterface
{
    public function apply(
        DiscountAmountMatrix $discountAmountMatrix,
        PriceInterface $shipmentPriceService,
        DiscountSetContainerInterface $discountSetContainerService,
        DiscountSetInterface $discountSetService,
        InputItem $inputItem,
        float $priceBeforeAnyDiscountsOnItem,
        float $priceAfterDiscountsAppliedOnDiscountSetPastItems
    ): float;
}
