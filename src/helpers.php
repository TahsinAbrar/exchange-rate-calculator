<?php

if (!function_exists('callToApi')) {


    /**
     * @param $url
     * @param $data
     * @param array $header
     * @param string $method
     * @param string $port
     * @return bool|string
     */
    function callToApi($url, $method = 'POST', $data = [], $header = [])
    {
        /* TODO: We can use a separate Service to call any external API by using guzzlehttp */
        try {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($curl, CURLOPT_TIMEOUT, 20);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            if ($method == 'POST') {
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlErrorNo = curl_errno($curl);
            curl_close($curl);

            if ($code == 200 & !($curlErrorNo)) {
                return $response;
            } else {
                $logMessage = "FAILED TO CONNECT WITH EXTERNAL API due to ". $err . " and cURL error code is " . $code . " and response is: " . $response;
                writeToLog($logMessage, 'alert');

                return false;
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}

if (!function_exists('writeToLog')) {
    /**
     * @param $message
     * @param string $type
     */
    function writeToLog($message, $type = 'GENERAL')
    {
        try {
            date_default_timezone_set('Asia/Dhaka');

            $log = strtoupper($type) . ':' . $message . ' | ' . date("F j, Y, g:i a") . PHP_EOL .
                '-------------------------' . PHP_EOL;

            //Save string to log, use FILE_APPEND to append.
            file_put_contents(__DIR__ . '/logs/project.log', $log, FILE_APPEND);
        } catch (\Exception $exception) {
            // need to write exception handling code
        }

    }
}

if (!function_exists('cleanEmptyString')) {

    /**
     * @param $input
     * @return string
     */
    function cleanEmptyString(string $input) {
        return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $input);
    }
}

if (!function_exists("formatNumberValue"))
{
    /**
     * @param $number
     * @return float
     */
    function formatNumberValue(float $number)
    {
        return round($number, 2);
    }
}
