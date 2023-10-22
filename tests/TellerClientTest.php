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
//        $token = getenv('TELLER_TEST_TOKEN');
//        $teller = new TellerClient($token);
//        $result = $teller->listAccounts();
//        $this->assertJson($result);
//    }

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