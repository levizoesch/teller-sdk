<?php

namespace LeviZoesch\TellerSDK\Tests;

use LeviZoesch\TellerSDK\EnvironmentTypes;

class TellerEnvironmentTypesTest extends TestCase
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