<?php


namespace ExchangeRateCalculator;


use ExchangeRateCalculator\Services\BinCheckerService;
use ExchangeRateCalculator\Services\ExchangeRateCheckerService;

class ExchangeRateCalc
{
    private $filePath;
    private $exchangeRateService;

    /**
     * ExchangeRateController constructor.
     */
    public function __construct()
    {
        $this->exchangeRateService = new ExchangeRateCheckerService();
    }

    /**
     * @param $inputs
     * @throws \Exception
     */
    public function setInputFile(array $inputs)
    {
        // confirm that there is only two inputs i.e. only 1 file name as input. More than two inputs are not allowed.
        if (count($inputs) != 2) {
            throw new \Exception("Sorry, we only accept 1 file input.\nFor example, run this command: \nphp app.php input.txt");
        }

        $this->setInputFilePath($inputs[1]);
    }

    /**
     * @return mixed
     */
    public function getInputFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param $filePath
     */
    public function setInputFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @throws \Exception
     */
    public function readFileData()
    {
        if ($this->getInputFilePath() == null || !filesize($this->getInputFilePath())) {
            throw new \Exception("File is required or cannot be empty.");
        }

        $inputFileData = cleanEmptyString(file_get_contents($this->getInputFilePath()));
        $data = explode("\n", $inputFileData);

        if (!is_array($data)) {
            throw new \Exception("Sorry, the input file is invalid. Please provide a valid data and try again.");
        }

        return $data;
    }

    public function initiateProcess()
    {
        try {
            // read the file data. But before calling this method, we must need to call setInputFile() method.
            $inputs = $this->readFileData();

            // Here, call the exchange rate API only one time. Then check in each iterator with the existing data.
            $this->exchangeRateService->initiate();

            foreach ($inputs as $row) {

                // Validate the input data as json payload.
                $payload = json_decode($row, true);

                if ($payload == null) { continue; }

                $response = $this->calculate($payload);

                if ($response && $response['status'] == true && $response['amount'] != null) {
                    echo $response['amount'] . "\n";
                } else {
                    // TODO: It is not instructed about what to do if we get other response instead of 200 - Success response.
                    echo "--- ". $response['message'] . " ---\n";
                }
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            exit;
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function calculate($data)
    {
        $response = [
            'status' => false,
            'amount' => null,
            'message' => null,
        ];

        try {
            // Validate if there is any required data missing or not.
            if (!(isset($data['bin']) && isset($data['amount']) && isset($data['currency']))) {
                throw new \Exception("Missing required data");
            }

            // Check the bin result via calling the API each time
            $binCheckerService = new BinCheckerService();
            $binCheckerService->init($data['bin']);

            // Determine the bin is within EU or not
            $isEuCurrency = $binCheckerService->isEuCurrency($binCheckerService->getAlpha2Code());

            $response['amount'] = $this->exchangeRateService->calculateRate($data['currency'], $data['amount'], $isEuCurrency);
            $response['status'] = true;

        } catch (\Exception $exception) {
            $response['status'] = false;
            $response['message'] = $exception->getMessage();
        } finally {
            return $response;
        }
    }
}
