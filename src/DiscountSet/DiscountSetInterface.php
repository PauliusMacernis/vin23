<?php
declare(strict_types = 1);

namespace DiscountSet;

use DataMatrix\DiscountAmountMatrix;
use DiscountSetContainer\DiscountSetContainerInterface;
use Output\OutputItem;
use Price\PriceInterface;

interface DiscountSetInterface
{
    public function getPriceWithDiscountsApplied(PriceInterface $shipmentPriceService, DiscountSetContainerInterface $discountSetContainerService, float $priceWithoutDiscount, OutputItem $output, DiscountAmountMatrix $discountAmountMatrix): float;
}
