<?php


namespace ExchangeRateCalculator\Services;


class ExchangeRateCheckerService
{
    /**
     * @property string baseUrl
     */
    private $baseUrl;
    private $exchangeRates = [];

    /**
     * ExchangeRateChecker constructor.
     */
    public function __construct()
    {
        $this->baseUrl = 'https://api.exchangeratesapi.io/latest';
    }

    /**
     * @throws \Exception
     */
    public function initiate()
    {
        $request = callToApi($this->baseUrl,'GET');
        $response = json_decode($request, true);

        if (!($response && isset($response['rates']))) {
            throw new \Exception("Sorry, there is a problem with your internet connection. Please check your internet and try again.");
        }

        $this->setExchangeRatesList($response['rates']);
    }

    /**
     * @param $currency
     * @param $providedAmount
     * @param bool $isEu
     * @return float
     */
    public function calculateRate($currency, $providedAmount, bool $isEu)
    {

        $rate = $this->getExchangeRate($currency);

        if ($currency == 'EUR' || $rate <= 0) {
            $amount = $providedAmount;
        } elseif ($currency != 'EUR' || $rate > 0) {
            $amount = $providedAmount / $rate;
        }

        $calculatedAmount = $amount * ($isEu == true ? 0.01 : 0.02);

        return formatNumberValue($calculatedAmount);
    }

    protected function getExchangeRate($currency)
    {
        return $this->exchangeRates[$currency] ?? 0;
    }

    /**
     * @return array
     */
    protected function getExchangeRatesList(): array
    {
        return $this->exchangeRates;
    }

    /**
     * @param array $rates
     */
    protected function setExchangeRatesList(array $rates)
    {
        $this->exchangeRates = $rates;
    }
}
