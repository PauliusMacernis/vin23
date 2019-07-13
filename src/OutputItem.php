<?php

use Price\ShipmentPriceInterface;
use Input\InputItem;
use Math\Math;

class OutputItem
{
    private $date;
    private $packageSizeCode;
    private $carrierCode;

    private $shipmentPriceWithDiscounts;
    private $shipmentPriceWithoutDiscounts;
    private $shipmentDiscount;

    // @TODO: Dependency should be based on Interface, not Class
    // @TODO: Package, Carrier may be developed as objects on their own
    // @TODO: There may be Price object too.
    // @TODO: ReducedPrice and ShipmentPrice may be objects extending from Price
    // @TODO: Calculations may be performed within "attached" trait construction as well
    public function __construct(InputItem $input, ShipmentPriceInterface $shipmentPrice)
    {
        $this->setDate($input->getDateTime());
        $this->setPackageSizeCode($input->getPackageSizeCode());
        $this->setCarrierCode($input->getCarrierCode());

        $this->setShipmentPriceWithoutDiscounts(
            $shipmentPrice->getShipmentPrice(
                $input->getCarrierCode(),
                $input->getPackageSizeCode()
            )
        );
        $this->setShipmentPriceWithDiscount(
            $shipmentPrice->getShipmentPriceWithDiscounts(
                $this->getShipmentPriceWithoutDiscounts(),
                $input
            )
        );
        $this->setShipmentDiscount(Math::aMinusB(
            $this->getShipmentPriceWithoutDiscounts(), $this->getShipmentPriceWithDiscounts()
        ));
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    private function setDate(DateTime $date): void
    {
        $this->date = $date;
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

    // @TODO: Consider using more precise value carrier than float e.g. Math objects.
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
}