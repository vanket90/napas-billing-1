<?php

return [
    'napas' => [
        'billing' => [
            'api_url' => env('NAPAS_BILLING_API_URL', null),
            'user_id' => env('NAPAS_BILLING_USER_ID', null),
            'user_password' => env('NAPAS_BILLING_USER_PASSWORD', null),
            'napas_public_key' => env('NAPAS_BILLING_PUBLIC_KEY', null),
            '9pay_private_key' => env('NAPAS_BILLING_9PAY_PRIVATE_KEY', null),
            '9pay_public_key' => env('NAPAS_BILLING_9PAY_PUBLIC_KEY', null),
            '9pay_password' => env('NAPAS_BILLING_9PAY_PASSWORD', null),
        ]
    ]
];
