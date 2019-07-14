<?php
declare(strict_types = 1);

namespace Input;

use DataMatrix\DiscountAmountMatrix;
use DataMatrix\TransactionsCountMatrix;
use DataTransformer\StringIsoToObjectDateTime;
use DiscountSetContainer\DiscountSetContainerInterface;
use Exception\IgnorableItemException;
use Output\OutputItem;
use Price\PriceInterface;
use RuntimeException;
use SplFileObject;

final class InputManager
{
    private const DATA_COLUMNS_EXPECTED_PER_LINE = 3;

    /** @var SplFileObject $file */
    private $file;
    private $lineNumber;
    /** @var TransactionsCountMatrix */
    private $transactionsCountMatrix;


    public function __construct(string $pathToInputFile)
    {
        $this->setFile($pathToInputFile);
        $this->setLineNumber(0);
        $this->setTransactionsCountMatrix(new TransactionsCountMatrix());
    }

    public function openTransactionFile(): void
    {
        $this->getFile()->rewind();
        $this->setLineNumber(0);
        $this->resetTransactionsCountMatrix();
    }

    public function getNextTransactionLine(): ?string
    {
        $this->getFile()->next();
        $this->setLineNumber($this->getLineNumber() + 1);
        if ($this->getFile()->eof() === true) {

            return null;
        }

        return $this->getFile()->current();
    }

    public function convertTransactionLineToObject(string $input, PriceInterface $shipmentPriceService, DiscountSetContainerInterface $discountSetContainer, DiscountAmountMatrix $discountAmountMatrix): OutputItem
    {
        $inputArray = preg_split("/[\s]+/", $input, -1, PREG_SPLIT_NO_EMPTY);
        $this->validateInputOrThrowException($inputArray, $input);

        [$dateTime, $packageSizeCode, $carrierCode] = $inputArray;
        $inputItemDateTime = (new StringIsoToObjectDateTime())->transformIsoDateToDateTimeObject($dateTime);

        $this->getTransactionsCountMatrix()->addValue($inputItemDateTime, $carrierCode, $packageSizeCode, $this->getLineNumber());

        return new OutputItem(
            $inputItemDateTime,
            $packageSizeCode,
            $carrierCode,
            $shipmentPriceService,
            $discountSetContainer,
            $discountAmountMatrix,
            $this->getTransactionsCountMatrix()
        );
    }

    /**
     * @param string[] $inputArray
     */
    protected function validateInputOrThrowException(array $inputArray, string $input): void
    {
        if (count($inputArray) !== self::DATA_COLUMNS_EXPECTED_PER_LINE) {
            throw new IgnorableItemException(sprintf(
                'Input file data failure. Expected %s columns, got %s. Row: "%s"',
                self::DATA_COLUMNS_EXPECTED_PER_LINE,
                count($inputArray),
                $input
            ));
        }
    }

    private function setFile(string $pathToInputFile): void
    {
        if (!is_file($pathToInputFile)) {
            throw new RuntimeException(sprintf('Input file is not found: %s', $pathToInputFile));
        }
        $this->file = new SplFileObject($pathToInputFile);
    }

    private function getFile(): SplFileObject
    {
        return $this->file;
    }

    private function setLineNumber($lineNumber): void
    {
        $this->lineNumber = $lineNumber;
    }

    private function getLineNumber()
    {
        return $this->lineNumber;
    }

    private function setTransactionsCountMatrix(TransactionsCountMatrix $transactionsCountMatrix): void
    {
        $this->transactionsCountMatrix = $transactionsCountMatrix;
    }

    private function getTransactionsCountMatrix(): TransactionsCountMatrix
    {
        return $this->transactionsCountMatrix;
    }

    private function resetTransactionsCountMatrix(): void
    {
        $this->transactionsCountMatrix->reset();
    }
}
