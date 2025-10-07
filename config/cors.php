<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://192.168.1.21',
        'http://192.168.1.21/pos',
        'http://192.168.1.21/ease-pos-cashier',
        'https://pos.kerritsolutions.com',
        'https://admin.kerritsolutions.com',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // ✅ Required for Sanctum
];


