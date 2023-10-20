<?php

namespace LeviZoesch\TellerSDK;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TellerSDKServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('teller-sdk')
            ->hasConfigFile();
    }
}
