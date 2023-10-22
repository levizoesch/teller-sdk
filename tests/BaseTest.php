<?php
namespace LeviZoesch\TellerSDK\Tests;


use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

abstract class BaseTest extends \Orchestra\Testbench\TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        // make sure, our .env file is loaded
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);
        parent::getEnvironmentSetUp($app);
    }
}
