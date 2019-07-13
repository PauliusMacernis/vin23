<?php

// @TODO: This may be used via interface+abstract class so structures like OutputCli, OutputFile, others may be attached.
// @TODO: Some templating may be introduced here as well probably.
class Output
{
    public function outputLine(OutputItem $item): void
    {
        // @TODO: check all the formats, especially float ones, separators, spaces, etc.
        echo sprintf(
            "%s %s %s %.2f %.2f %.2f\n",
            $item->getDate()->format('Y-m-d'),
            $item->getPackageSizeCode(),
            $item->getCarrierCode(),
            $item->getShipmentPriceWithoutDiscounts(),
            $item->getShipmentPriceWithDiscounts(),
            $item->getShipmentDiscount()
        );
    }

    public function outputDone(): void
    {
        // @TODO: This method marks the job being done. Most likely, it is redundant and we may remove it.
        echo 'DONE.';
    }
}
