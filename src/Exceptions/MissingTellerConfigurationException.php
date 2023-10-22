<?php

namespace LeviZoesch\TellerSDK\Exceptions;

use Exception;

class MissingTellerConfigurationException extends Exception
{
    public function render($request)
    {
        return response("The teller configuration file is missing. "
            . " Please run 'php artisan vendor:publish --tag=teller-sdk-config' to generate.", 404);
    }

}
