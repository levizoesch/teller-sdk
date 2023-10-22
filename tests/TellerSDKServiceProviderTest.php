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
        // Verify that the service provider is registered
        $this->assertTrue($this->app->getProvider(TellerSDKServiceProvider::class) instanceof TellerSDKServiceProvider);

        // You can add more assertions here, such as verifying configuration loading or other actions performed by the service provider.
    }
}