<?php

namespace Input;

// @TODO: global try-catch
// @TODO: Use/create more specific exceptions to throw, not a general RunntimeException, - applies everywhere
// @TODO: We may consider different validator class for all validate* methods.
// @TODO: We may consider different validator class for all validateDate* methods.

use DataMatrix\TransactionsCountMatrix;
use DateTime;

class InputItem
{
    private $dateTime;
    private $packageSizeCode;
    private $carrierCode;

    // @TODO: $itemNumber and $itemNumberThisMonth may be moved to separate trait (or alike construction/s) attached to related discount object
    private $itemNumber;
    private $transactionsCountMatrix;

    /**
     * InputItem constructor.
     * @param array|false|string $input
     * @see https://www.php.net/manual/en/splfileobject.fgets.php
     * @TODO: Take care of the cases when $input comes as array or false
     */
    public function __construct(int $itemNumber, TransactionsCountMatrix $transactionsCountMatrix, DateTime $itemDateTime, string $packageSizeCode, string $carrierCode)
    {
        $this->setItemNumber($itemNumber);
        $this->setTransactionsCountMatrix($transactionsCountMatrix);
        $this->setDateTime($itemDateTime);
        $this->setPackageSizeCode($packageSizeCode);
        $this->setCarrierCode($carrierCode);
    }

    private function setDateTime(DateTime $date): void
    {
        $this->dateTime = $date;
    }

    private function setPackageSizeCode($packageSizeCode): void
    {
        // @TODO: Packages size code validation
        $this->packageSizeCode = $packageSizeCode;
    }

    private function setCarrierCode($carrierCode): void
    {
        // @TODO: carrier code validation
        $this->carrierCode = (string) $carrierCode;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function getPackageSizeCode(): string
    {
        return $this->packageSizeCode;
    }

    public function getCarrierCode(): string
    {
        return $this->carrierCode;
    }

    private function setItemNumber($itemNumber): void
    {
        $this->itemNumber = $itemNumber;
    }

    private function setTransactionsCountMatrix(TransactionsCountMatrix $transactionsCountMatrix)
    {
        $this->transactionsCountMatrix = $transactionsCountMatrix;
    }

    public function getTransactionsCountMatrix(): TransactionsCountMatrix
    {
        return $this->transactionsCountMatrix;
    }
}
