<?php


namespace ExchangeRateCalculator\Services;


interface BinCheckerInterface
{

    public function getBaseUrl();

    public function setBaseUrl($url);

    public function getAlpha2Code();

    public function setAlpha2Code($alpha2Code);

    public function init($bin);

    public function isEuCurrency($currency);

}
