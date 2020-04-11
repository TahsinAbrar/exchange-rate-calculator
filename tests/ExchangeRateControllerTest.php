<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ExchangeRateCalculator\ExchangeRateCalc;

class ExchangeRateControllerTest extends TestCase
{

    /**
     * expectException \Exception
     * @throws \Exception
     */
    public function testExpectExceptionForSetInputFile()
    {
        $this->expectException(\Exception::class);

        $inputs = [
            __DIR__ .'/../app.php',
        ];
        $exchangeRate = new ExchangeRateCalc();

        $exchangeRate->setInputFile($inputs);

        $inputs = [
            __DIR__ .'/../app.php',
            __DIR__ .'/../input.txt',
            'test.py',
        ];

        $exchangeRate->setInputFile($inputs);
    }

    public function testSetInputFile()
    {
        $exchangeRate = new ExchangeRateCalc();

        $sampleInputFilePath = __DIR__ .'/../input.txt';
        $inputs = [
            __DIR__ .'/../app.php',
            $sampleInputFilePath
        ];

        $exchangeRate->setInputFile($inputs);

        $this->assertEquals($sampleInputFilePath, $exchangeRate->getInputFilePath());
    }

    /**
     * expectException \Exception
     * @throws \Exception
     */
    public function testExpectFileRequiredExceptionForReadFileData()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("File is required or cannot be empty.");

        $exchangeRate = new ExchangeRateCalc();
        $exchangeRate->readFileData();
    }

    /**
     * expectException \Exception
     * @throws \Exception
     */
    public function testFileEmptyExceptionForReadFileData()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("File is required or cannot be empty.");

        $exchangeRate = new ExchangeRateCalc();
        $sampleInputFilePath = __DIR__ .'/sample/input2.txt';

        $inputs = [
            __DIR__ .'/../app.php',
            $sampleInputFilePath
        ];

        $exchangeRate->setInputFile($inputs);
        $exchangeRate->readFileData();
    }

    /*public function testSuccessInitiateProcess()
    {
        $exchangeRate = new ExchangeRateController();
        $sampleInputFilePath = __DIR__ .'/../input.txt';

        $inputs = [
            __DIR__ .'/../app.php',
            $sampleInputFilePath
        ];

        $exchangeRate->setInputFile($inputs);
        $exchangeRate->initiateProcess();
    }*/

    public function testCalculateMethod()
    {
        $exchangeRate = new ExchangeRateCalc();
        //{"bin":"516793","amount":"50.00","currency":"USD"}
        $data = [
            'bin' =>'516793',
            'amount' => 50.00,
            'currency' => 'USD'
        ];
        $response = $exchangeRate->calculate($data);
        $this->assertIsArray($response);
        $this->assertTrue($response['status']);

        $response = $exchangeRate->calculate(['516793', 50.00, 'USD']);
        $this->assertIsArray($response);
        $this->assertFalse($response['status']);
    }
}
