<?php

namespace TellerSDK;

use Exception;
use GuzzleHttp\Exception\RequestException;
use TellerSDK\Enums\EnvironmentTypes;
use TellerSDK\Exceptions\InvalidEnvironmentException;
use TellerSDK\Exceptions\MissingAccessTokenException;
use TellerSDK\Exceptions\MissingTellerCertException;
use TellerSDK\Exceptions\MissingTellerConfigurationException;
use TellerSDK\Exceptions\MissingTellerKeyException;
use TellerSDK\Exceptions\EnvironmentNullException;
use TellerSDK\Exceptions\UnexpectedErrorResponseException;
use GuzzleHttp\Client;

class TellerClient
{
    private string $BASE_URL = 'https://api.teller.io';

    private string $access_token;

    /**
     * @throws MissingAccessTokenException
     */
    public function __construct($accessToken)
    {
        if ($accessToken === null){
            throw new MissingAccessTokenException();
        }
        $this->access_token = $accessToken;
    }

    public function listAccounts()
    {
        return $this->get('/accounts');
    }

    public function accountsCount(): int
    {
        return count($this->listAccounts());
    }

    public function getAccountDetails($accountId)
    {
        return $this->get("/accounts/{$accountId}/details");
    }

    public function getAccountBalances($accountId)
    {
        return $this->get("/accounts/{$accountId}/balances");
    }

    public function listAccountTransactions($accountId)
    {
        return $this->get("/accounts/{$accountId}/transactions");
    }

    public function getTransactionDetails($accountId, $transactionId)
    {
        return $this->get("/accounts/{$accountId}/transactions/{$transactionId}");
    }

    public function destroyAccount($accountId)
    {
        return $this->destroy("/accounts/" . $accountId);
    }

    public function listAccountPayees($accountId, $scheme)
    {
        return $this->get("/accounts/{$accountId}/payments/{$scheme}/payees");
    }

    public function createAccountPayee($accountId, $data)
    {
        return $this->post("/accounts/{$accountId}/payees", $data);
    }

    public function createAccountPayment($accountId, $scheme, $data)
    {
        return $this->post("/accounts/{$accountId}/payments/{$scheme}", $data);
    }

    public function listIdentity()
    {
        return $this->get('/identity');
    }

    public function get($path)
    {
        return json_decode($this->request('GET', $path), false, 512, JSON_THROW_ON_ERROR);
    }

    private function post($path, $data)
    {
        return json_decode($this->request('POST', $path, $data), false, 512, JSON_THROW_ON_ERROR);
    }

    private function destroy($path)
    {
        return json_decode($this->request('DELETE', $path), false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws MissingTellerConfigurationException
     * @throws MissingTellerCertException
     * @throws MissingTellerKeyException
     * @throws Exception
     */
    private function request($method, $path, $data = null): bool|string
    {
        $configFilePath = config_path('teller.php');

        if (!file_exists($configFilePath)) {
            throw new MissingTellerConfigurationException();
        }

        $url = $this->BASE_URL . $path;
        $accessToken = base64_encode($this->access_token . ':');

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $accessToken,
        ];

        $tellerEnvironment = config('teller.ENVIRONMENT');

        if ($tellerEnvironment === null) {
            throw new EnvironmentNullException();
        }

        if (!in_array($tellerEnvironment, EnvironmentTypes::LIST)) {
            throw new InvalidEnvironmentException();
        }

        $client = new Client();

        $options = [
            'headers' => $headers,
        ];

        if ($data) {
            $options['json'] = $data;
        }

        if ($tellerEnvironment === EnvironmentTypes::PRODUCTION || $tellerEnvironment === EnvironmentTypes::DEVELOPMENT) {
            $certPath = config('teller.CERT_PATH');
            $keyPath = config('teller.KEY_PATH');

            if (!file_exists($certPath)) {
                throw new MissingTellerCertException();
            }

            if (!file_exists($keyPath)) {
                throw new MissingTellerKeyException();
            }

            $options['cert'] = [$certPath, $keyPath];
        }

        try {
            $response = $client->request($method, $url, $options);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            if ($statusCode === 200) {
                return $body;
            } else {
                $errorObj = json_decode($body, true);
                if ($errorObj && isset($errorObj['error'])) {
                    $errorCode = $errorObj['error']['code'];
                    $errorMessage = $errorObj['error']['message'];
                    return "Error (HTTP $statusCode): $errorCode - $errorMessage";
                } else {
                    throw new UnexpectedErrorResponseException();
                }
            }
        } catch (RequestException $e) {
            throw new Exception("Guzzle request failed: " . $e->getMessage());
        }
    }

}