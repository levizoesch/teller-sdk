<?php

use LeviZoesch\TellerSDK\TellerSDKServiceProvider;
use LeviZoesch\TellerSDK\Tests\TestCase;

class TellerSDKServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TellerSDKServiceProvider::class];
    }

    public function testTellerSDKServiceProviderIsRegistered()
    {
        $this->assertTrue($this->app->getProvider(TellerSDKServiceProvider::class) instanceof TellerSDKServiceProvider);
        $this->assertFileExists('config/teller.php');
    }
}