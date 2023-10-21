<?php

namespace LeviZoesch\TellerSDK;

use JsonException;
use LeviZoesch\TellerSDK\Enums\EnvironmentTypes;

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
    public function getAccountTransaction($accountId, $transactionId)
    {
        return $this->get("/accounts/{$accountId}/transactions/{$transactionId}");
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
    public function createAccountPayee($accountId, $scheme, $data)
    {
        return $this->post("/accounts/{$accountId}/payments/{$scheme}/payees", $data);
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
    private function request($method, $path, $data = null): bool|string
    {
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
        //$error = curl_error($curl);

        curl_close($curl);

        return $response;
    }


}
