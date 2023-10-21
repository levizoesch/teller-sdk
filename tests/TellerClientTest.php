<?php


use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\TestCase;

class TellerClientTest extends TestCase
{

    public function testTesting()
    {
        $variable = true;

        if (Date::now()->toString() === '01/01/2000') {
            $variable = false;
        }

        $this->assertTrue($variable, 'The value is not true.');
    }
}
