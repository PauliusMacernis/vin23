<?php
declare(strict_types = 1);

namespace DataTransformer;

use DateTime;
use Exception\IgnorableItemException;
use Throwable;

class StringIsoToObjectDateTime
{
    public function transformIsoDateToDateTimeObject(string $date): DateTime
    {
        $this->validateDateOrThrowException($date);

        try {
            return new DateTime($date);
        } catch (Throwable $exception) {
            throw new IgnorableItemException(sprintf('Given value is not suitable for DateTime object. Given: %s', $date));
        }
    }

    private function validateDateOrThrowException(string $date): void
    {
        $this->validateDateSeparatorsOrThrowException($date);

        $dateAsArray = explode('-', $date);
        $this->validateDateYearMinMaxOrThrowException($dateAsArray[0]);
        $this->validateDateMonthMinMaxOrThrowException($dateAsArray[1]);
        $this->validateDateDayMinMaxOrThrowException($dateAsArray[2]);
    }

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

    private function validateDateYearMinMaxOrThrowException(string $year): void
    {
        if ((int) $year < 1) {
            throw new IgnorableItemException(sprintf('Year value cannot be lower than 1. Got: %d', (int) $year));
        }
        if ((int) $year > 9999) {
            throw new IgnorableItemException(sprintf('Year value cannot be greater than 9999. Got: %d', (int) $year));
        }
    }

    private function validateDateMonthMinMaxOrThrowException(string $month): void
    {
        if ((int) $month < 1) {
            throw new IgnorableItemException(sprintf('Month value cannot be lower than 1. Got: %d', (int) $month));
        }
        if ((int) $month > 12) {
            throw new IgnorableItemException(sprintf('Month value cannot be greater than 12. Got: %d', (int) $month));
        }
    }

    private function validateDateDayMinMaxOrThrowException(string $day): void
    {
        if ((int) $day < 1) {
            throw new IgnorableItemException(sprintf('Month value cannot be lower than 1. Got: %d', (int) $day));
        }
        if ((int) $day > 31) {
            throw new IgnorableItemException(sprintf('Month value cannot be greater than 31. Got: %d', (int) $day));
        }
    }
}
