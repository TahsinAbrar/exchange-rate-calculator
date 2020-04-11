<?php


namespace ExchangeRateCalculator\Services;


interface ExchangeRateCheckerInterface
{
    public function getBaseUrl();

    public function setBaseUrl($url);

}
