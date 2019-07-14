<?php
declare(strict_types = 1);

namespace DataMatrix;

use DateTimeInterface;

class TransactionsCountMatrix
{
    private $matrix;

    public function addValue(DateTimeInterface $dateTime, string $carrierCode, string $packageSizeCode, int $lineNumber): void
    {
        $indexYear = $dateTime->format('Y');
        $indexMonth = $dateTime->format('m');
        $indexDay = $dateTime->format('d'); // This index is probably redundant

        // Note: Matrix columns may be optimized (reduced) for better performance sacrificing details of the data
        if (!isset($this->matrix[$indexYear][$indexMonth][$indexDay][$carrierCode][$packageSizeCode][$lineNumber])) {
            $this->matrix[$indexYear][$indexMonth][$indexDay][$carrierCode][$packageSizeCode][$lineNumber] = 0;

            return;
        }

        $this->matrix[$indexYear][$indexMonth][$indexDay][$carrierCode][$packageSizeCode][$lineNumber] = 1;
    }

    public function reset(): void
    {
        $this->matrix = [];
    }

    public function countItemsOfSizeOfCarrierInMonth(string $packageSizeCode, string $carrierCode, DateTimeInterface $month): int
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
