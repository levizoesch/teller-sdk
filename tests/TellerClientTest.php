<?php

namespace LeviZoesch\TellerSDK\Tests;
use LeviZoesch\TellerSDK\Exceptions\MissingAccessTokenException;
use LeviZoesch\TellerSDK\Exceptions\MissingTellerConfigurationException;
use LeviZoesch\TellerSDK\TellerClient;

class TellerClientTest extends BaseTest
{

//    /**
//     * @throws MissingAccessTokenException
//     */
//    public function testListAccounts()
//    {
//        $teller = new TellerClient(config('teller.TEST_TOKEN'));
//        $result = $teller->listAccounts();
//        $this->assertJson($result);
//    }

    public function testTellerTestTokenIsNotDefined()
    {
        $this->assertNull(config('teller.TEST_TOKEN'), 'The teller test token is missing from the environment file, or undefined.');
    }

    public function testMissingTellerConfigurationExceptionThrown() {
        $this->expectException(MissingTellerConfigurationException::class);

        throw new MissingTellerConfigurationException();
    }

}