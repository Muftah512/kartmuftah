<?php

return [
    'mikrotik' => [
        'url' => env('MIKROTIK_URL', 'https://192.168.88.1'),
        'user' => env('MIKROTIK_USER', 'admin'),
        'password' => env('MIKROTIK_PASSWORD', ''),
    ],

    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v17.0/1234567890'),
        'api_key' => env('WHATSAPP_API_KEY', 'your-whatsapp-api-key'),
    ],
];