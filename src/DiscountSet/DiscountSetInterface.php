<?php
declare(strict_types = 1);

namespace DiscountSet;

use DataMatrix\DiscountAmountMatrix;
use DiscountSetContainer\DiscountSetContainerInterface;
use Input\InputItem;
use Price\PriceInterface;

interface DiscountSetInterface
{
    public function getPriceWithDiscountsApplied(PriceInterface $shipmentPriceService, DiscountSetContainerInterface $discountSetContainerService, float $priceWithoutDiscount, InputItem $input, DiscountAmountMatrix $discountAmountMatrix): float;
}
