<?php

require __DIR__ . '/vendor/autoload.php';

use \ExchangeRateCalculator\ExchangeRateCalc;

try {

    $exchangeRateObj = new ExchangeRateCalc();
    $exchangeRateObj->setInputFile((array) $argv);
    $exchangeRateObj->initiateProcess();

} catch (\Exception $exception) {
    echo $exception->getMessage();
    exit;
}
