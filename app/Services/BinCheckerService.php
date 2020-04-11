<?php


namespace App\Services;


class BinCheckerService
{
    /**
     * @property string baseUrl
     */
    private $baseUrl;
    private $alpha2Code;

    /**
     * ExchangeRateChecker constructor.
     */
    public function __construct()
    {
        $this->baseUrl = 'https://lookup.binlist.net';
    }

    /**
     * @param $bin
     * @throws \Exception
     */
    public function init($bin)
    {
        $url = $this->baseUrl . '/' . $bin;
        $response = callToApi($url,'GET');
        $response = json_decode($response, true);

        if (!isset($response['country']['alpha2'])) {
            throw new \Exception("Sorry, either you are doing too many requests or there is a problem with your internet connection. Please try again.");
        }

        $this->setAlpha2Code($response['country']['alpha2']);
    }

    /**
     * @param $currency
     * @return bool
     */
    public function isEuCurrency($currency) {

        $euroCurrencyList = [
            'AT', 'BE', 'BG',
            'CY', 'CZ', 'DE',
            'DK', 'EE', 'ES',
            'FI', 'FR', 'GR',
            'HR', 'HU', 'IE',
            'IT', 'LT', 'LU',
            'LV', 'MT', 'NL',
            'PO', 'PT', 'RO',
            'SE', 'SI', 'SK',
        ];

        return in_array($currency, $euroCurrencyList);
    }

    /**
     * @return mixed
     */
    public function getAlpha2Code()
    {
        return $this->alpha2Code;
    }

    /**
     * @param mixed $alpha2Code
     */
    public function setAlpha2Code($alpha2Code)
    {
        $this->alpha2Code = $alpha2Code;
    }
}
