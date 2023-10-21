<?php
return [
    /**
     * The location in the laravel project where your keys are stored.
     * base_path('your/path/to/keys/teller_pk.pem')
     */
    'key_path' => base_path('teller_pk.pem'),
    'cert_path' => base_path('teller_cert.pem'),
    'TELLER' => [
        'ENVIRONMENT' => env('TELLER_ENVIRONMENT', env('TELLER_ENVIRONMENT')),
        'APP_ID' => env('TELLER_APP_ID', env('TELLER_APP_ID')),
        'PUBLIC_KEY' => env('TELLER_PUBLIC_KEY', env('TELLER_PUBLIC_KEY')),
        'WEBHOOK_SECRET_KEY' => env('TELLER_WEBHOOK_SECRET_KEY', env('TELLER_WEBHOOK_SECRET_KEY'))
    ]
];