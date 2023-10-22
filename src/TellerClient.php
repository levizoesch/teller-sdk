<?php

namespace LeviZoesch\TellerSDK;

use Exception;
use JsonException;
use LeviZoesch\TellerSDK\Enums\EnvironmentTypes;
use LeviZoesch\TellerSDK\Exceptions\MissingTellerConfigurationException;

class TellerClient
{
    private string $BASE_URL = 'https://api.teller.io';

    private string $access_token;

    public function __construct($accessToken)
    {
        $this->access_token = $accessToken;
    }

    /**
     * @throws JsonException
     */
    public function listAccounts()
    {
        return $this->get('/accounts');
    }

    /**
     * @throws JsonException
     */
    public function accountsCount(): int
    {
        return count($this->listAccounts());
    }

    /**
     * @throws JsonException
     */
    public function getAccountDetails($accountId)
    {
        return $this->get("/accounts/{$accountId}/details");
    }

    /**
     * @throws JsonException
     */
    public function getAccountBalances($accountId)
    {
        return $this->get("/accounts/{$accountId}/balances");
    }

    /**
     * @throws JsonException
     */
    public function listAccountTransactions($accountId)
    {
        return $this->get("/accounts/{$accountId}/transactions");
    }

    /**
     * @throws JsonException
     */
    public function getTransactionDetails($accountId, $transactionId)
    {
        return $this->get("/accounts/{$accountId}/transactions/{$transactionId}");
    }

    /**
     * @throws JsonException
     */
    public function destroyAccount($accountId)
    {
        return $this->destroy("/accounts/" . $accountId);
    }

    /**
     * @throws JsonException
     */
    public function listAccountPayees($accountId, $scheme)
    {
        return $this->get("/accounts/{$accountId}/payments/{$scheme}/payees");
    }

    /**
     * @throws JsonException
     */
    public function createAccountPayee($accountId, $data)
    {
        return $this->post("/accounts/{$accountId}/payees", $data);
    }

    /**
     * @throws JsonException
     */
    public function createAccountPayment($accountId, $scheme, $data)
    {
        return $this->post("/accounts/{$accountId}/payments/{$scheme}", $data);
    }

    /**
     * @throws JsonException
     */
    public function listIdentity()
    {
        return $this->get('/identity');
    }

    /**
     * @throws JsonException
     */
    public function get($path)
    {
        return json_decode($this->request('GET', $path), false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    private function post($path, $data)
    {
        return json_decode($this->request('POST', $path, $data), false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    private function destroy($path)
    {
        return json_decode($this->request('DELETE', $path), false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    private function request($method, $path, $data = null): bool|string
    {

        $configFilePath = config_path('teller.php');

        if (!file_exists($configFilePath)) {
            throw new MissingTellerConfigurationException();
        }
        $url = $this->BASE_URL . $path;
        $accessToken = base64_encode($this->access_token .':');

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . $accessToken
        ];

        $tellerEnvironment = config('teller.ENVIRONMENT');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers
        ]);

        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_THROW_ON_ERROR));
        }

        if ($tellerEnvironment === EnvironmentTypes::PRODUCTION || $tellerEnvironment === EnvironmentTypes::DEVELOPMENT) {
            curl_setopt($curl, CURLOPT_SSLCERT, config('teller.CERT_PATH'));
            curl_setopt($curl, CURLOPT_SSLKEY, config('teller.KEY_PATH'));
        }

        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get the HTTP status code
        $error = curl_error($curl);

        curl_close($curl);

        if ($statusCode === 200) {
            return $response;
        } else {
            $errorObj = json_decode($response, true);
            if ($errorObj && isset($errorObj['error'])) {
                $errorCode = $errorObj['error']['code'];
                $errorMessage = $errorObj['error']['message'];
                return "Error (HTTP $statusCode): $errorCode - $errorMessage";
            } else {
                return "Error (HTTP $statusCode): $error";
            }
        }
    }


}
