<?php
declare(strict_types = 1);

namespace DiscountSetContainer;

use DataMatrix\DiscountAmountMatrix;
use DiscountSet\DiscountSetByV;
use DiscountSet\DiscountSetInterface;
use Input\InputItem;
use Math\Math;
use Price\PriceInterface;
use RuntimeException;

/**
 * Deals with sets of discounts polled together for any reason.
 *
 */
class DiscountSetContainerFrance implements DiscountSetContainerInterface
{
    private const DISCOUNT_SETS_APPLIED_IN_ORDER = [
        DiscountSetByV::class
    ];

    public function getPriceWithDiscountsApplied(PriceInterface $shipmentPriceService, float $priceWithoutDiscount, InputItem $output, DiscountAmountMatrix $discountAmountMatrix): float
    {
        $priceAfterApplyingDiscountSets = Math::getNumber(0.0);

        /** @var DiscountSetInterface $discountSetObject */
        foreach (self::DISCOUNT_SETS_APPLIED_IN_ORDER as $discountSet) {
            $discountSetObject = new $discountSet();
            $this->validateDiscountSetOrThrowException($discountSetObject);

            $priceAfterApplyingDiscountSet = $discountSetObject->getPriceWithDiscountsApplied($shipmentPriceService, $this, $priceWithoutDiscount, $output, $discountAmountMatrix);
            $priceAfterApplyingDiscountSets = Math::aPlusB($priceAfterApplyingDiscountSets, $priceAfterApplyingDiscountSet);
        }

        return $priceAfterApplyingDiscountSets;
    }

    private function validateDiscountSetOrThrowException($discountSet): void
    {
        if (!$discountSet instanceof DiscountSetInterface) {
            throw new RuntimeException(sprintf(
                'Discount set must implement interface: %s',
                DiscountSetInterface::class
            ));
        }
    }
}
