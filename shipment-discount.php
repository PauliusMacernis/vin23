<?php

// @TODO: Should we tweak timezone settings anyhow for this project? I assume we use and trust server settings for now.
// @TODO: Assuming input file is ordered by transaction date ASC

define('APPLICATION_DECIMAL_PRECISION', 2);
spl_autoload_register(static function (string $className) {
    include sprintf(
        'src%s%s.php',
        DIRECTORY_SEPARATOR,
        strtr($className, ['\\' => DIRECTORY_SEPARATOR])
    );
});

use DataMatrix\DiscountAmountMatrix;
use DiscountSetContainer\DiscountSetContainerInFrance;
use Input\Input;
use Price\PriceFranceEur;

// @TODO: Find a better way for the file path to get into Input constructor. It may be coming from CLI as an argument.
$input = new Input('input.txt');
$output = new Output();

$input->openTransactionFile();

$shipmentPriceService = new PriceFranceEur();
$discountSetContainer = new DiscountSetContainerInFrance();
$discountAmountMatrix = new DiscountAmountMatrix();

while ($line = $input->getNextTransactionLine()) {
    $item = new OutputItem($line, $shipmentPriceService, $discountSetContainer, $discountAmountMatrix);
    $output->outputLine($item);
}

$output->outputDone();
