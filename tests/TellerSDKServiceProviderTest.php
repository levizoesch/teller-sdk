<?php

use TellerSDK\TellerSDKServiceProvider;
use TellerSDK\Tests\BaseTest;

class TellerSDKServiceProviderTest extends BaseTest
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