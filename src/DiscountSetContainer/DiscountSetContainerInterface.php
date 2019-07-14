<?php
declare(strict_types = 1);

namespace DiscountSetContainer;

use DataMatrix\DiscountAmountMatrix;
use Input\InputItem;
use Price\PriceInterface;

interface DiscountSetContainerInterface
{
    public function getPriceWithDiscountsApplied(PriceInterface $shipmentPriceService, float $priceWithoutDiscount, InputItem $output, DiscountAmountMatrix $discountAmountMatrix): float;
}
