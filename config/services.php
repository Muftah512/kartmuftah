<?php

   return [
            'mikrotik' => [
                'host' => env('MIKROTIK_HOST', '84.252.95.215'),
                'port'     => (int) env('MIKROTIK_PORT_PORT', 7756),
                'timeout'  => (int) env('MT_TIMEOUT', 10),
                'username' => env('MIKROTIK_USERNAME', 'MUFTAH'),
                'password' => env('MIKROTIK_PASSWORD', ''),
                'customer_name' => env('MIKROTIK_CUSTOMER_NAME', 'admin'),
            ],

    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v17.0/1234567890'),
        'api_key' => env('WHATSAPP_API_KEY', 'your-whatsapp-api-key'),
    ],
];
