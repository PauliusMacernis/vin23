<?php
declare(strict_types = 1);

define('APPLICATION_DECIMAL_PRECISION', 2);
include_once 'core/features/enableClassAutoload.php';

use DataMatrix\DiscountAmountMatrix;
use DiscountSetContainer\DiscountSetContainerFrance;
use Exception\IgnorableItemException;
use Input\InputManager;
use Output\OutputManager;
use Price\PriceFranceEur;

$pathToInputFile = trim($argv[1]);
$inputManager = new InputManager($pathToInputFile);
$outputManager = new OutputManager();

$inputManager->openTransactionFile();

$shipmentPriceService = new PriceFranceEur();
$discountSetContainer = new DiscountSetContainerFrance();
$discountAmountMatrix = new DiscountAmountMatrix();

while ($lineAsString = $inputManager->getNextTransactionLine()) {
    try {
        $lineAsObject = $inputManager->convertTransactionLineToObject($lineAsString, $shipmentPriceService, $discountSetContainer, $discountAmountMatrix);
    } catch (IgnorableItemException $exception) {
        $outputManager->outputLineIgnored($lineAsString);
        continue;
    }

    $outputManager->outputLine($lineAsObject);
}

$outputManager->outputDone();
