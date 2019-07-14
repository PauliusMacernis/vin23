<?php
declare(strict_types=1);

namespace Output;

use Input\InputItem;
use Math\Math;

class OutputManager
{
    public function outputLine(InputItem $item): void
    {
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

    private function getFormattedShipmentDiscount(InputItem $item): string
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
