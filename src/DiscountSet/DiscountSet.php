<?php

namespace DiscountSet;

use DataMatrix\DiscountAmountMatrix;
use Discount\DiscountInterface;
use DiscountSetContainer\DiscountSetContainerInterface;
use Input\InputItem;
use Math\Math;
use Price\PriceInterface;
use RuntimeException;

abstract class DiscountSet implements DiscountSetInterface
{
    private $discountAmountMatrixAfterApplyDiscount;

    protected function applyDiscount(string $className): float
    {
        if (!class_exists($className)) {
            throw new RuntimeException(sprintf('Discount class %s does not exist.', $className));
        }

        $discountObject = new $className;
        if (!$discountObject instanceof DiscountInterface) {
            throw new RuntimeException(sprintf('All discount classes must implement common interface: %s', DiscountInterface::class));
        }

        $arguments = func_get_args();
        array_shift($arguments);

        /** @var DiscountAmountMatrix          $discountAmountMatrix */
        /** @var PriceInterface                $shipmentPriceService */
        /** @var DiscountSetContainerInterface $discountSetContainerService */
        /** @var DiscountSetInterface          $thisDiscountSetService */
        /** @var float                         $priceBeforeAnyDiscountsOnItem */
        /** @var float                         $priceAfterDiscountsAppliedOnDiscountSetItems */
        /** @var InputItem                    $outputItem */
        [$discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $thisDiscountSetService, $priceBeforeAnyDiscountsOnItem, $priceAfterDiscountsAppliedOnDiscountSetItems, $outputItem] = $arguments;

        if (null === $this->discountAmountMatrixAfterApplyDiscount) { // First item analyzed, there was no any of "after apply discount" yet.
            $this->discountAmountMatrixAfterApplyDiscount = new DiscountAmountMatrix();
        }

        $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem = $discountObject->apply(
            $this->discountAmountMatrixAfterApplyDiscount,
            $shipmentPriceService,
            $discountSetContainerService,
            $thisDiscountSetService,
            $outputItem,
            $priceBeforeAnyDiscountsOnItem,
            $priceAfterDiscountsAppliedOnDiscountSetItems
        );

        $this->addToDiscountAccumulatedInDiscountSet($discountAmountMatrix, $outputItem, $discountSetContainerService, $thisDiscountSetService, $discountObject, $priceAfterDiscountsAppliedOnDiscountSetItems, $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem);

        return $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem;
    }

    protected function addToDiscountAccumulatedInDiscountSet(DiscountAmountMatrix $discountAmountMatrix, InputItem $output, DiscountSetContainerInterface $discountSetContainerService, DiscountSetInterface $thisDiscountSetService, DiscountInterface $discountObject, float $priceAfterDiscountsAppliedOnDiscountSetItems, float $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem): void
    {
        $discountAmountMatrix->addValue($output->getDateTime(), $discountSetContainerService, $thisDiscountSetService, $discountObject, Math::aMinusB($priceAfterDiscountsAppliedOnDiscountSetItems, $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem));
        $this->discountAmountMatrixAfterApplyDiscount = $discountAmountMatrix;
    }
}
