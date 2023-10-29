<?php

namespace TellerSDK\Tests;
use TellerSDK\Exceptions\MissingAccessTokenException;
use TellerSDK\Exceptions\MissingTellerConfigurationException;
use TellerSDK\TellerClient;

class TellerClientTest extends BaseTest
{

    public function testConfigMatchesEnv()
    {
        $envToken = getenv('TELLER_TEST_TOKEN');
        $confToken = config('teller.TEST_TOKEN');

        $this->assertSame($envToken,$confToken);
    }


    /**
    * @throws MissingAccessTokenException
    */
    public function testListAccounts()
    {
        $token = config('teller.TEST_TOKEN');
        $teller = new TellerClient($token);
        $result = $teller->listAccounts();
        $this->assertIsArray($result);
    }

    public function testAccountsCount()
    {
        $token = config('teller.TEST_TOKEN');
        $teller = new TellerClient($token);
        $result = $teller->accountsCount();
        $this->assertIsInt($result);
    }

    public function testListAccountDetails()
    {
        $token = config('teller.TEST_TOKEN');
        $teller = new TellerClient($token);
        $result = $teller->listAccounts();
        $accountId = $result[0]->id;
        $details = $teller->getAccountDetails($accountId);
        $this->assertSame($accountId, $details->account_id);
    }

    public function testListAccountTransactions()
    {
        $token = config('teller.TEST_TOKEN');
        $teller = new TellerClient($token);
        $result = $teller->listAccounts();
        $accountId = $result[0]->id;
        $transactions = $teller->listAccountTransactions($accountId);
        $this->assertSame($accountId, $transactions[0]->account_id);
    }

    public function testListAccountTransactionDetails()
    {
        $token = config('teller.TEST_TOKEN');
        $teller = new TellerClient($token);
        $result = $teller->listAccounts();
        $accountId = $result[0]->id;
        $transactions = $teller->listAccountTransactions($accountId);
        $details = $teller->getTransactionDetails($accountId, $transactions[0]->id);

        $this->assertSame($accountId, $details->account_id);
    }

    public function testListAccountBalances()
    {
        $token = config('teller.TEST_TOKEN');
        $teller = new TellerClient($token);
        $result = $teller->listAccounts();
        $accountId = $result[0]->id;
        $balance = $teller->getAccountBalances($accountId);
        $this->assertSame($accountId, $balance->account_id);
    }

    public function testListAccountsMissingAccessTokenExceptionThrown()
    {
        $token = null;
        $this->expectException(MissingAccessTokenException::class);
        $teller = new TellerClient($token);
    }

    public function testTellerTestTokenIsDefined()
    {
        $token = getenv('TELLER_TEST_TOKEN');
        $this->assertIsString($token);
    }

    public function testMissingTellerConfigurationExceptionThrown() {
        $this->expectException(MissingTellerConfigurationException::class);

        throw new MissingTellerConfigurationException();
    }

}
