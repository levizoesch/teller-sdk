<?php
return [
    'TELLER' => [
        'ENVIRONMENT' => env('TELLER_ENVIRONMENT', env('TELLER_ENVIRONMENT')),
        'APP_ID' => env('TELLER_APP_ID', env('TELLER_APP_ID')),
        'PUBLIC_KEY' => env('TELLER_PUBLIC_KEY', env('TELLER_PUBLIC_KEY')),
        'WEBHOOK_SECRET_KEY' => env('TELLER_WEBHOOK_SECRET_KEY', env('TELLER_WEBHOOK_SECRET_KEY'))
    ]
];