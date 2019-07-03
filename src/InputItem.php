<?php

// @TODO: global try-catch
// @TODO: Use/create more specific exceptions to throw, not a general RunntimeException, - applies everywhere
// @TODO: We may consider different validator class for all validate* methods.
// @TODO: We may consider different validator class for all validateDate* methods.


class InputItem
{
    private $date;
    private $packageSizeCode;
    private $carrierCode;

    /**
     * InputItem constructor.
     * @param array|false|string $input
     * @see https://www.php.net/manual/en/splfileobject.fgets.php
     */
    public function __construct($input)
    {
        // @TODO: Regex may be better for cases with multiple space separator and so on but most likely a bit slower.
        $inputArray = explode(' ', trim($input));
        // @TODO: The value may be exported to the constant. I leave it here because column indexes (0,1,2) are here too.
        $dataColumnsExpected = 3;

        $this->validateInputOrThrowException($inputArray, $dataColumnsExpected, $input);
        $this->setDate($inputArray[0]);
        $this->setPackageSizeCode($inputArray[1]);
        $this->setCarrierCode($inputArray[2]);
    }

    /**
     * @param string[] $inputArray
     */
    protected function validateInputOrThrowException(array $inputArray, int $dataColumnsExpected, string $input): void
    {
        if (count($inputArray) !== $dataColumnsExpected) {
            throw new \RuntimeException(sprintf(
                'Input file data failure. Expected %s columns, got %s. Row: "%s"',
                $dataColumnsExpected,
                count($inputArray),
                $input
            ));
        }
    }

    private function setDate(string $date): void
    {
        $this->validateDateOrThrowException($date);
        $this->date = new DateTime($date);
    }

    private function setPackageSizeCode($packageSizeCode): void
    {
        // @TODO: Packages size code validation
        $this->packageSizeCode = (string) $packageSizeCode;
    }

    private function setCarrierCode($carrierCode): void
    {
        // @TODO: carrier code validation
        $this->carrierCode = (string) $carrierCode;
    }

    private function validateDateOrThrowException($date)
    {
        // @TODO: There may be completely different and possibly the better approach on validating the data, e.g. creating date object + try-catch

        // @TODO: Improve data validation by adding more rules
        $this->validateDateSeparatorsOrThrowException($date);

        // @TODO: DRY with explode on dash.
        $dateAsArray = explode('-', $date);
        $this->validateDateYearOrThrowException($dateAsArray[0]);
        $this->validateDateMonthOrThrowException($dateAsArray[1]);
        $this->validateDateDayOrThrowException($dateAsArray[2]);

        // @TODO: Do we need to check for dates AFTER the date of today (server time, timezone)?
    }

    /**
     * @param $date
     */
    private function validateDateSeparatorsOrThrowException(string $date): void
    {
        $dashSeparatedDataEntityGroupsExpected = 3;
        $dateAsArray = explode('-', $date);
        if (count($dateAsArray) !== $dashSeparatedDataEntityGroupsExpected) { // ISO format: YYYY-MM-DD
            throw new \RuntimeException(sprintf(
                'Date is not of expected "YYYY-MM-DD" format. Should be %s, got %s. Date: %s',
                $dashSeparatedDataEntityGroupsExpected,
                count($dateAsArray),
                $date
            ));
        }
    }

    private function validateDateYearOrThrowException(string $year): void
    {
        if ((int) $year < 1) {
            // @TODO: use other types than %s for sprintf templates, e.g. integers may be of another type than %s.
            // @TODO: Most likely, all validation rules should be outputting the line number or line as string?
            throw new RuntimeException(sprintf('Year value cannot be lower than 1. Got: %s', (int) $year));
        }

        if ((int) $year > 9999) {
            throw new RuntimeException(sprintf('Year value cannot be greater than 9999. Got: %s', (int) $year));
        }

    }

    private function validateDateMonthOrThrowException(string $month)
    {
        // @TODO: Implement validation
        return $month;
    }

    private function validateDateDayOrThrowException($day)
    {
        // @TODO: Implement validation
        return $day;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getPackageSizeCode(): string
    {
        return $this->packageSizeCode;
    }

    public function getCarrierCode(): string
    {
        return $this->carrierCode;
    }
}
