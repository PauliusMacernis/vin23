<?php

define('APPLICATION_DECIMAL_PRECISION', 2);
include_once 'core/features/enableClassAutoload.php';

use DataMatrix\DiscountAmountMatrix;
use DiscountSetContainer\DiscountSetContainerInFrance;
use Input\Input;
use Price\PriceFranceEur;
use Exception\IgnorableItemException;


$input = new Input(trim($argv[1]));
$output = new Output();

$input->openTransactionFile();

$shipmentPriceService = new PriceFranceEur();
$discountSetContainer = new DiscountSetContainerInFrance();
$discountAmountMatrix = new DiscountAmountMatrix();

while ($line = $input->getNextTransactionLine()) {
    try {
        $lineAsInputItem = $input->convertLineToObject($line);
        $item = new OutputItem($lineAsInputItem, $shipmentPriceService, $discountSetContainer, $discountAmountMatrix);
    } catch (IgnorableItemException $exception) {
        $output->outputLineIgnored($lineAsInputItem);
        continue;
    }

    $output->outputLine($item);
}

$output->outputDone();
