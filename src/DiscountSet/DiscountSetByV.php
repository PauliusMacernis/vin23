<?php
declare(strict_types = 1);

namespace DiscountSet;

use DataMatrix\DiscountAmountMatrix;
use Discount\AccumulatedDiscountsCannotExceedTenEuroPerCalendarMonth;
use Discount\AllSShipmentsAlwaysMatchLowestSPackagePriceAmongProviders;
use Discount\DiscountInterface;
use Discount\ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth;
use DiscountSetContainer\DiscountSetContainerInterface;
use Math\Math;
use Output\OutputItem;
use Price\PriceInterface;
use RuntimeException;

class DiscountSetByV implements DiscountSetInterface
{
    private $discountAmountMatrixAfterApplyDiscount;

    public function getPriceWithDiscountsApplied(PriceInterface $shipmentPriceService, DiscountSetContainerInterface $discountSetContainerService, float $priceWithoutDiscount, OutputItem $output, DiscountAmountMatrix $discountAmountMatrix): float
    {
        // Rule#1: All S shipments should always match the lowest S package price among the providers.
        $price = $this->applyDiscount(AllSShipmentsAlwaysMatchLowestSPackagePriceAmongProviders::class, $discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $this, $priceWithoutDiscount, $priceWithoutDiscount, $output);

        // Rule#2: Third L shipment via LP should be free, but only once a calendar month.
        $price = $this->applyDiscount(ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth::class, $discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $this, $priceWithoutDiscount, $price, $output);

        // Rule#3: Accumulated discounts cannot exceed 10 € in a calendar month. If there are not enough funds to fully cover a discount this calendar month, it should be covered partially.
        $price = $this->applyDiscount(AccumulatedDiscountsCannotExceedTenEuroPerCalendarMonth::class, $discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $this, $priceWithoutDiscount, $price, $output);

        return $price;
    }

    // @TODO: Move this to parent/abstract class?
    private function applyDiscount(string $className): float
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
        /** @var OutputItem                    $outputItem */
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

    private function addToDiscountAccumulatedInDiscountSet(DiscountAmountMatrix $discountAmountMatrix, OutputItem $output, DiscountSetContainerInterface $discountSetContainerService, DiscountSetInterface $thisDiscountSetService, DiscountInterface $discountObject, float $priceAfterDiscountsAppliedOnDiscountSetItems, float $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem): void
    {
        $discountAmountMatrix->addValue($output->getDateTime(), $discountSetContainerService, $thisDiscountSetService, $discountObject, Math::aMinusB($priceAfterDiscountsAppliedOnDiscountSetItems, $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem));
        $this->discountAmountMatrixAfterApplyDiscount = $discountAmountMatrix;
    }
}
