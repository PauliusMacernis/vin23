<?php


class OutputItem
{
    private $date;
    private $packageSizeCode;
    private $carrierCode;
    private $reducedShipmentPrice;
    private $shipmentDiscount;

    // @TODO: Dependency should be based on Interface, not Class
    public function __construct(InputItem $input)
    {
        $this->setDate($input->getDate());
        $this->setPackageSizeCode($input->getPackageSizeCode());

        // @TODO: Implement calculations and valua passing to the methods bellow
        $this->setCarrierCode('');
        $this->setReducedShipmentPrice(0.00);
        $this->setShipmentDiscount(0.00);
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
    public function getReducedShipmentPrice(): float
    {
        return $this->reducedShipmentPrice;
    }

    private function setReducedShipmentPrice(float $reducedShipmentPrice): void
    {
        $this->reducedShipmentPrice = $reducedShipmentPrice;
    }

    public function getShipmentDiscount(): float
    {
        return $this->shipmentDiscount;
    }

    private function setShipmentDiscount(float $shipmentDiscount): void
    {
        $this->shipmentDiscount = $shipmentDiscount;
    }
}
