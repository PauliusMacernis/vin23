<?php
declare(strict_types = 1);

define('APPLICATION_DECIMAL_PRECISION', 2);
include_once 'core/features/enableClassAutoload.php';

use DataMatrix\DiscountAmountMatrix;
use DiscountSetContainer\DiscountSetContainerFrance;
use Input\InputManager;
use Price\PriceFranceEur;
use Exception\IgnorableItemException;

$pathToInputFile = trim($argv[1]);
$inputManager = new InputManager($pathToInputFile);
$output = new Output();

$inputManager->openTransactionFile();

$shipmentPriceService = new PriceFranceEur();
$discountSetContainer = new DiscountSetContainerFrance();
$discountAmountMatrix = new DiscountAmountMatrix();

while ($lineAsString = $inputManager->getNextTransactionLine()) {
    try {
        $lineAsObject = $inputManager->convertTransactionLineToObject($lineAsString, $shipmentPriceService, $discountSetContainer, $discountAmountMatrix);
    } catch (IgnorableItemException $exception) {
        $output->outputLineIgnored($lineAsString);
        continue;
    }

    $output->outputLine($lineAsObject);
}

$output->outputDone();
