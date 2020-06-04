<?php

return [
    'napas' => [
        'billing' => [
            'api_url' => env('NAPAS_BILLING_API_URL', null),
            'user_id' => env('NAPAS_BILLING_USER_ID', null),
            'user_password' => env('NAPAS_BILLING_USER_PASSWORD', null)
        ]
    ]
];
