<?php

namespace LeviZoesch\TellerSDK\Tests;

use LeviZoesch\TellerSDK\Enums\EnvironmentTypes;

class TellerEnvironmentTypesTest extends BaseTest
{

    public function testEnvironmentTypeSandbox() {
        $this->assertIsString('sandbox', EnvironmentTypes::SANDBOX);
    }

    public function testEnvironmentTypeDevelopment() {
        $this->assertIsString('development', EnvironmentTypes::DEVELOPMENT);
    }

    public function testEnvironmentTypeProduction() {
        $this->assertIsString('production', EnvironmentTypes::PRODUCTION);
    }

}