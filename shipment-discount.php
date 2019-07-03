<?php

// @TODO: Should we tweak timezone settings anyhow for this project? I assume we use and trust server settings for now.
// @TODO: Assuming input file is ordered by transaction date ASC

// @TODO: Require logic should be centralized, e.g. done via autoloading.
require_once 'src/Input.php';
require_once 'src/InputItem.php';
require_once 'src/Output.php';
require_once 'src/OutputItem.php';

// @TODO: Find a better way for the file path to get into Input constructor. It may be coming from CLI as an argument.
$input = new Input('input.txt');
$output = new Output();

$input->openTransactionFile();

while ($line = $input->getNextTransactionLine()) {
    $item = new OutputItem($line);
    $output->outputLine($item);
}

$output->outputDone();
