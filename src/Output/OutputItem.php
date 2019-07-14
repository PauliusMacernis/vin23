<?php
declare(strict_types = 1);

namespace Output;

use DataMatrix\DiscountAmountMatrix;
use DataMatrix\TransactionsCountMatrix;
use DateTime;
use DiscountSetContainer\DiscountSetContainerInterface;
use Math\Math;
use Price\PriceInterface;

class OutputItem
{
    private $dateTime;
    private $packageSizeCode;
    private $carrierCode;

    private $shipmentPriceWithDiscounts;
    private $shipmentPriceWithoutDiscounts;
    private $shipmentDiscount;

    private $transactionsCountMatrix;

    // @TODO: Dependency should be based on Interface, not Class
    // @TODO: Package, Carrier may be developed as objects on their own
    public function __construct(DateTime $itemDateTime, string $packageSizeCode, string $carrierCode, PriceInterface $shipmentPrice, DiscountSetContainerInterface $discountSetContainer, DiscountAmountMatrix $discountAmountMatrix, TransactionsCountMatrix $transactionsCountMatrix)
    {
        $this->setTransactionsCountMatrix($transactionsCountMatrix);

        $this->setDateTime($itemDateTime);
        $this->setPackageSizeCode($packageSizeCode);
        $this->setCarrierCode($carrierCode);
        $this->setShipmentPriceWithoutDiscounts(
            $shipmentPrice->getShipmentPrice(
                $this->getCarrierCode(), $this->getPackageSizeCode()
            )
        );
        $this->setShipmentPriceWithDiscount(
            $discountSetContainer->getPriceWithDiscountsApplied(
                $shipmentPrice, $this->getShipmentPriceWithoutDiscounts(), $this, $discountAmountMatrix
            )
        );
        $this->setShipmentDiscount(Math::aMinusB(
            $this->getShipmentPriceWithoutDiscounts(), $this->getShipmentPriceWithDiscounts()
        ));
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    private function setDateTime(DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getPackageSizeCode(): string
    {
        return $this->packageSizeCode;
    }

    private function setPackageSizeCode(string $packageSizeCode): void
    {
        $this->packageSizeCode = $packageSizeCode;
    }

    public function getCarrierCode(): string
    {
        return $this->carrierCode;
    }

    private function setCarrierCode(string $carrierCode): void
    {
        $this->carrierCode = $carrierCode;
    }

    public function getShipmentPriceWithDiscounts(): float
    {
        return $this->shipmentPriceWithDiscounts;
    }

    private function setShipmentPriceWithDiscount(float $shipmentPriceWithDiscount): void
    {
        $this->shipmentPriceWithDiscounts = $shipmentPriceWithDiscount;
    }

    public function getShipmentDiscount(): float
    {
        return $this->shipmentDiscount;
    }

    private function setShipmentDiscount(float $shipmentDiscount): void
    {
        $this->shipmentDiscount = $shipmentDiscount;
    }

    private function setShipmentPriceWithoutDiscounts(float $shipmentPriceWithoutDiscounts): void
    {
        $this->shipmentPriceWithoutDiscounts = $shipmentPriceWithoutDiscounts;
    }

    public function getShipmentPriceWithoutDiscounts(): float
    {
        return $this->shipmentPriceWithoutDiscounts;
    }

    public function getTransactionsCountMatrix(): TransactionsCountMatrix
    {
        return $this->transactionsCountMatrix;
    }

    private function setTransactionsCountMatrix(TransactionsCountMatrix $transactionsCountMatrix): void
    {
        $this->transactionsCountMatrix = $transactionsCountMatrix;
    }
}
