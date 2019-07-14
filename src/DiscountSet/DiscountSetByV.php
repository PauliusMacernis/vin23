<?php
declare(strict_types = 1);

namespace DiscountSet;

use DataMatrix\DiscountAmountMatrix;
use Discount\AccumulatedDiscountsCannotExceedTenEuroPerCalendarMonth;
use Discount\AllSShipmentsAlwaysMatchLowestSPackagePriceAmongProviders;
use Discount\DiscountInterface;
use Discount\ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth;
use DiscountSetContainer\DiscountSetContainerInterface;
use Input\InputItem;
use Math\Math;
use Price\PriceInterface;
use RuntimeException;

class DiscountSetByV implements DiscountSetInterface
{
    private $discountAmountMatrixAfterApplyDiscount;

    public function getPriceWithDiscountsApplied(PriceInterface $shipmentPriceService, DiscountSetContainerInterface $discountSetContainerService, float $priceWithoutDiscount, InputItem $input, DiscountAmountMatrix $discountAmountMatrix): float
    {
        // Rule#1: All S shipments should always match the lowest S package price among the providers.
        $price = $this->applyDiscount(AllSShipmentsAlwaysMatchLowestSPackagePriceAmongProviders::class, $discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $this, $priceWithoutDiscount, $priceWithoutDiscount, $input);

        // Rule#2: Third L shipment via LP should be free, but only once a calendar month.
        $price = $this->applyDiscount(ThirdLShipmentViaLpShouldBeFreeButOnlyOnceACalendarMonth::class, $discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $this, $priceWithoutDiscount, $price, $input);

        // Rule#3: Accumulated discounts cannot exceed 10 € in a calendar month. If there are not enough funds to fully cover a discount this calendar month, it should be covered partially.
        $price = $this->applyDiscount(AccumulatedDiscountsCannotExceedTenEuroPerCalendarMonth::class, $discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $this, $priceWithoutDiscount, $price, $input);

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
        /** @var InputItem                     $input */
        [$discountAmountMatrix, $shipmentPriceService, $discountSetContainerService, $thisDiscountSetService, $priceBeforeAnyDiscountsOnItem, $priceAfterDiscountsAppliedOnDiscountSetItems, $input] = $arguments;

        if (null === $this->discountAmountMatrixAfterApplyDiscount) { // First item analyzed, there was no any of "after apply discount" yet.
            $this->discountAmountMatrixAfterApplyDiscount = new DiscountAmountMatrix();
        }

        $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem = $discountObject->apply(
            $this->discountAmountMatrixAfterApplyDiscount,
            $shipmentPriceService,
            $discountSetContainerService,
            $thisDiscountSetService,
            $input,
            $priceBeforeAnyDiscountsOnItem,
            $priceAfterDiscountsAppliedOnDiscountSetItems
        );

        $this->addToDiscountAccumulatedInDiscountSet($discountAmountMatrix, $input, $discountSetContainerService, $thisDiscountSetService, $discountObject, $priceBeforeAnyDiscountsOnItem, $priceAfterDiscountsAppliedOnDiscountSetItems, $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem);

        return $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem;
    }

    private function addToDiscountAccumulatedInDiscountSet(DiscountAmountMatrix $discountAmountMatrix, InputItem $input, DiscountSetContainerInterface $discountSetContainerService, DiscountSetInterface $thisDiscountSetService, DiscountInterface $discountObject, float $priceBeforeAnyDiscountsOnItem, float $priceAfterDiscountsAppliedOnDiscountSetItems, float $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem): void
    {
        $discountAmountMatrix->addValue($input->getDateTime(), $discountSetContainerService, $thisDiscountSetService, $discountObject, Math::aMinusB($priceAfterDiscountsAppliedOnDiscountSetItems, $priceAfterDiscountsAppliedOnDiscountSetItemsAndThisItem));
        $this->discountAmountMatrixAfterApplyDiscount = $discountAmountMatrix;
    }
}
