<?php

require __DIR__ . '/vendor/autoload.php';

use \App\Controllers\ExchangeRateController;

try {

    $exchangeRateObj = new ExchangeRateController();
    $exchangeRateObj->setInputFile((array) $argv);
    $exchangeRateObj->initiateProcess();

} catch (\Exception $exception) {
    echo $exception->getMessage();
    exit;
}
