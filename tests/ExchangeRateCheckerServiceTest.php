<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ExchangeRateCalculator\Services\ExchangeRateCheckerService;

class ExchangeRateCheckerServiceTest extends TestCase
{

    public function testCalculateRate()
    {
        $service = new ExchangeRateCheckerService();
        $service->initiate();

        $this->assertEquals(1, $service->calculateRate('EUR', 100.00, 1));

        //$this->assertEquals("0.92", $service->calculateRate('USD', 50.00, 0)); // It could vary as the value is dynamic
    }
}
