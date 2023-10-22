<?php

namespace LeviZoesch\TellerSDK\Tests;
use LeviZoesch\TellerSDK\Exceptions\MissingTellerConfigurationException;
use LeviZoesch\TellerSDK\TellerClient;
use PHPUnit\Framework\MockObject\MockObject;

class TellerClientTest extends TestCase
{

    public function testListAccounts()
    {
        $teller = new TellerClient(config('teller.TEST_TOKEN'));
        $result = $teller->listAccounts();
        $this->assertJson($result);

    }

    public function testTellerTestTokenIsNotDefined()
    {
        $this->assertNull(config('teller.TEST_TOKEN'), 'The teller test token is missing from the environment file, or undefined.');
    }

    public function testMissingTellerConfigurationExceptionThrown() {
        $this->expectException(MissingTellerConfigurationException::class);

        throw new MissingTellerConfigurationException();
    }

}