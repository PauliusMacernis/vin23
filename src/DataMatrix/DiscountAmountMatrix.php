<?php
declare(strict_types = 1);

namespace DataMatrix;

use DateTimeInterface;
use Discount\DiscountInterface;
use DiscountSet\DiscountSetInterface;
use DiscountSetContainer\DiscountSetContainerInterface;
use Math\Math;

class DiscountAmountMatrix
{
    private $matrix;

    public function addValue(DateTimeInterface $dateTime, DiscountSetContainerInterface $discountSetContainer, DiscountSetInterface $discountSet, DiscountInterface $discountItem, float $amount): void
    {
        $indexYear = $dateTime->format('Y');
        $indexMonth = $dateTime->format('m');
        $indexDay = $dateTime->format('d'); // This index is probably redundant

        $discountSetContainerClass = get_class($discountSetContainer);
        $discountSetClass = get_class($discountSet);
        $discountItemClass = get_class($discountItem);

        // Note: Matrix columns may be optimized (reduced) for better performance sacrificing details of the data
        if (!isset($this->matrix[$indexYear][$indexMonth][$indexDay][$discountSetContainerClass][$discountSetClass][$discountItemClass])) {
            $this->matrix[$indexYear][$indexMonth][$indexDay][$discountSetContainerClass][$discountSetClass][$discountItemClass] = 0;
        }

        $this->matrix[$indexYear][$indexMonth][$indexDay][$discountSetContainerClass][$discountSetClass][$discountItemClass] = Math::aPlusB(
            $this->matrix[$indexYear][$indexMonth][$indexDay][$discountSetContainerClass][$discountSetClass][$discountItemClass],
            $amount
        );
    }

    public function reset(): void
    {
        $this->matrix = [];
    }

    public function countDiscountsOfDiscountSetContainerDiscountSetInMonth(DiscountSetContainerInterface $discountSetContainer, DiscountSetInterface $discountSet, DateTimeInterface $month): float
    {
        $indexYear = $month->format('Y');
        $indexMonth = $month->format('m');

        $discountSetContainerClass = get_class($discountSetContainer);
        $discountSetClass = get_class($discountSet);

        // Data does not exist yet
        if (!isset($this->matrix[$indexYear])
            || false === array_key_exists($indexMonth, $this->matrix[$indexYear])
        ) {
            return 0;
        }

        // Data exists
        $discounts = Math::getNumber(0.0);
        foreach ($this->matrix[$indexYear][$indexMonth] as $indexDay => $discountSetContainers) {
            if (!isset($discountSetContainers[$discountSetContainerClass][$discountSetClass])) {
                continue;
            }
            foreach ($discountSetContainers[$discountSetContainerClass][$discountSetClass] as $discountAmount) {
                $discounts = Math::aPlusB($discounts, $discountAmount);
            }
        }

        return $discounts;
    }
}
