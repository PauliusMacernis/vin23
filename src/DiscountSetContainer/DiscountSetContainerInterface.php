<?php

namespace DiscountSetContainer;

use DataMatrix\DiscountAmountMatrix;
use Input\InputItem;
use Price\PriceInterface;

interface DiscountSetContainerInterface
{
    public function getPriceWithDiscountsApplied(PriceInterface $shipmentPriceService, float $priceWithoutDiscount, InputItem $input, DiscountAmountMatrix $discountAmountMatrix): float;
}
