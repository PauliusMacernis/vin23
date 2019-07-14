<?php
declare(strict_types=1);

namespace Output;

use Math\Math;

// @TODO: This may be used via interface+abstract class so structures like OutputCli, OutputFile, others may be attached.
// @TODO: More advanced templating may be introduced here as well later on.
class OutputManager
{
    public function outputLine(OutputItem $item): void
    {
        // @TODO: check all the formats, especially float ones, separators, spaces, etc.
        echo sprintf(
            "%s %s %s %s %s\n",
            $item->getDateTime()->format('Y-m-d'),
            $item->getPackageSizeCode(),
            $item->getCarrierCode(),
            $this->formatNumber($item->getShipmentPriceWithDiscounts()),
            $this->getFormattedShipmentDiscount($item)
        );
    }

    public function outputLineIgnored($originalLine): void
    {
        echo sprintf("%s Ignored\n", trim($originalLine));
    }

    /**
     * This method marks the job being done to the end.
     * It is "silenced" therefore looks like the redundant method for now. :(
     */
    public function outputDone(): void
    {
        echo '';
    }

    private function getFormattedShipmentDiscount(OutputItem $item): string
    {
        $discount = $item->getShipmentDiscount();
        if (Math::isAEqualB($discount, 0.0)) {
            return '-';
        }

        return $this->formatNumber($discount);
    }

    private function formatNumber(float $number): string
    {
        return number_format($number, APPLICATION_DECIMAL_PRECISION);
    }
}
