<?php
declare(strict_types = 1);

namespace DiscountSetContainer;

use DataMatrix\DiscountAmountMatrix;
use Output\OutputItem;
use Price\PriceInterface;

interface DiscountSetContainerInterface
{
    public function getPriceWithDiscountsApplied(PriceInterface $shipmentPriceService, float $priceWithoutDiscount, OutputItem $output, DiscountAmountMatrix $discountAmountMatrix): float;
}
