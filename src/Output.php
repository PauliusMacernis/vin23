<?php

use Math\Math;

// @TODO: This may be used via interface+abstract class so structures like OutputCli, OutputFile, others may be attached.
// @TODO: Some templating may be introduced here as well probably.
class Output
{
    public function outputLine(OutputItem $item): void
    {
        // @TODO: check all the formats, especially float ones, separators, spaces, etc.
        echo sprintf(
            "%s %s %s %.2f %.2f %s\n",
            $item->getDate()->format('Y-m-d'),
            $item->getPackageSizeCode(),
            $item->getCarrierCode(),
            $item->getShipmentPriceWithoutDiscounts(),
            $item->getShipmentPriceWithDiscounts(),
            $this->getFormattedShipmentDiscount($item)
        );
    }

    public function outputDone(): void
    {
        // @TODO: This method marks the job being done. Most likely, it is redundant and we may remove it.
        echo 'DONE.';
    }

    /**
     * @param OutputItem $item
     * @return float
     */
    private function getFormattedShipmentDiscount(OutputItem $item): string
    {
        $discount = $item->getShipmentDiscount();
        if (Math::isAEqualB($discount, 0.0)) {
            return '-';
        }

        // @TODO: Assuming the output of decimal and thousand separator symbols are based on server's locale settings.
        return number_format($discount, APPLICATION_DECIMAL_PRECISION);
    }
}
