<?php
declare(strict_types = 1);

namespace DataTransformer;

use DateTime;
use Exception\IgnorableItemException;

class StringIsoToObjectDateTime
{
    public function transformIsoDateToDateTimeObject(string $date): DateTime
    {
        $this->validateDateOrThrowException($date);
        return new DateTime($date);
    }

    private function validateDateOrThrowException($date): void
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
            throw new IgnorableItemException(sprintf(
                'Date is not of expected "YYYY-MM-DD" format. Should be %s, got %s. Date: %s',
                $dashSeparatedDataEntityGroupsExpected,
                count($dateAsArray),
                $date
            ));
        }
    }

    private function validateDateYearOrThrowException(string $year): void
    {
        if ((int)$year < 1) {
            // @TODO: use other types than %s for sprintf templates, e.g. integers may be of another type than %s.
            // @TODO: Most likely, all validation rules should be outputting the line number or line as string?
            throw new IgnorableItemException(sprintf('Year value cannot be lower than 1. Got: %s', (int)$year));
        }

        if ((int)$year > 9999) {
            throw new IgnorableItemException(sprintf('Year value cannot be greater than 9999. Got: %s', (int)$year));
        }

    }

    private function validateDateMonthOrThrowException(string $month): void
    {
        // @TODO: Implement validation
    }

    private function validateDateDayOrThrowException($day): void
    {
        // @TODO: Implement validation
    }
}
