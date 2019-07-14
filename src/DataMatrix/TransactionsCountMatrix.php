<?php
declare(strict_types = 1);

namespace DataMatrix;

use DateTime;

class TransactionsCountMatrix
{
    private $matrix;

    public function addValue(DateTime $dateTime, string $carrierCode, string $packageSizeCode, int $lineNumber): void
    {
        $indexYear = $dateTime->format('Y');
        $indexMonth = $dateTime->format('m');
        $indexDay = $dateTime->format('d'); // This index is probably redundant

        if (!isset($this->matrix[$indexYear][$indexMonth][$indexDay][$carrierCode][$packageSizeCode][$lineNumber])) {
            $this->matrix[$indexYear][$indexMonth][$indexDay][$carrierCode][$packageSizeCode][$lineNumber] = 0;
        }

        $this->matrix[$indexYear][$indexMonth][$indexDay][$carrierCode][$packageSizeCode][$lineNumber]++; // =1 all the time
    }

    public function reset(): void
    {
        $this->matrix = [];
    }

    // @TODO: Test if adding the new month resets the count of the month, eg. if the last entry is of 2015-02, adding 2015-03 entry should set count to 0 on 2015-03
    public function countItemsOfSizeOfCarrierInMonth(string $packageSizeCode, string $carrierCode, DateTime $month): int
    {
        $indexYear = $month->format('Y');
        $indexMonth = $month->format('m');

        // Data does not exist yet
        if (!isset($this->matrix[$indexYear])
            || false === array_key_exists($indexMonth, $this->matrix[$indexYear])
        ) {
            return 0;
        }

        // Data exists
        $count = 0;
        foreach ($this->matrix[$indexYear][$indexMonth] as $indexDay => $carriers) {
            if (!isset($carriers[$carrierCode][$packageSizeCode])) {
                continue;
            }
            $count += count($carriers[$carrierCode][$packageSizeCode]);
        }

        return $count;
    }
}
