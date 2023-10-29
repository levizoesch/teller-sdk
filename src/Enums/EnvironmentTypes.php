<?php

namespace TellerSDK\Enums;

use Illuminate\Validation\Rules\Enum;

final class EnvironmentTypes extends Enum
{

    public const SANDBOX = 'sandbox';
    public const DEVELOPMENT = 'development';
    public const PRODUCTION = 'production';

    public const LIST = [
        self::SANDBOX,
        self::DEVELOPMENT,
        self::PRODUCTION
    ];

}
