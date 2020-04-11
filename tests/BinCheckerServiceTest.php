<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Services\BinCheckerService;

class BinCheckerServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsEuCurrency()
    {
        $binCheckerService = new BinCheckerService();

        $this->assertTrue($binCheckerService->isEuCurrency('FR'));
        $this->assertFalse($binCheckerService->isEuCurrency('BD'));
    }
}
