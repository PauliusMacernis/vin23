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

// @TODO: namespaces - we should probably need them all over here and there if we extend the application
// @TODO: getters, setters in all classes.
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

    private function getLineNumber()
    {
        return $this->lineNumber;
    }

    private function setLineNumber($lineNumber): void
    {
        $this->lineNumber = $lineNumber;
    }

    private function resetTransactionsCountMatrix(): void
    {
        $this->transactionsCountMatrix->reset();
    }

    private function setTransactionsCountMatrix(TransactionsCountMatrix $transactionsCountMatrix): void
    {
        $this->transactionsCountMatrix = $transactionsCountMatrix;
    }

    public function convertTransactionLineToObject(string $input, PriceInterface $shipmentPriceService, DiscountSetContainerInterface $discountSetContainer, DiscountAmountMatrix $discountAmountMatrix): OutputItem
    {

        // @TODO: Regex may be better for cases with multiple space separator and so on but most likely a bit slower.
        $inputArray = explode(' ', trim($input));
        // @TODO: The value may be exported to the constant. I leave it here because column indexes (0,1,2) are here too.

        $this->validateInputOrThrowException($inputArray, $input);

        // @TODO: Create the new item via factory or something similar.
        $inputItemDateTime = (new StringIsoToObjectDateTime())->transformIsoDateToDateTimeObject($inputArray[0]);
        $packageSizeCode = $inputArray[1];
        $carrierCode = $inputArray[2];

        $this->transactionsCountMatrix->addValue($inputItemDateTime, $carrierCode, $packageSizeCode, $this->getLineNumber());

        return new OutputItem(
            $inputItemDateTime,
            $packageSizeCode,
            $carrierCode,
            $shipmentPriceService,
            $discountSetContainer,
            $discountAmountMatrix,
            $this->transactionsCountMatrix
        );
    }
}
