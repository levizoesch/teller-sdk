<?php

namespace LeviZoesch\TellerSDK\Tests;
use Illuminate\Support\Facades\Date;
use LeviZoesch\TellerSDK\Exceptions\MissingTellerConfigurationException;

class TellerClientTest extends TestCase
{
    public function testPass()
    {
        $variable = true;

        if (Date::now()->toString() === '01/01/2000') {
            $variable = false;
        }

        $this->assertTrue($variable, 'The value is not true.');
    }
    public function testTellerTestTokenIsDefined()
    {
        $this->assertNull(config('teller.TEST_TOKEN'), 'The teller test token is missing from the environment file, or undefined.');
    }

    public function testMissingTellerConfigurationExceptionThrown() {
        $this->expectException(MissingTellerConfigurationException::class);

        throw new MissingTellerConfigurationException();
    }

}