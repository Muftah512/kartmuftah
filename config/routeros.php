<?php

return [
    'connections' => [
        'default' => [
            'host'     => env('MIKROTIK_HOST', '84.252.95.215'),
            'user'     => env('MIKROTIK_USER', 'MUFTAH'),
            'pass'     => env('MIKROTIK_PASS', 'Aa775614422Ff'),
            'port'     => (int) env('MIKROTIK_PORT', 7756),
            'attempts' => (int) env('MIKROTIK_ATTEMPTS', 1),
            'delay'    => (int) env('MIKROTIK_DELAY', 0),
            'timeout'  => (int) env('MIKROTIK_TIMEOUT', 3),
        ],
    ],
];
