<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MikroTikService
{
    protected $baseUrl;
    protected $auth;

    public function __construct()
    {
        $this->baseUrl = config('services.mikrotik.base_url');
        $this->auth = [
            'username' => config('services.mikrotik.username'),
            'password' => config('services.mikrotik.password'),
        ];
    }

    public function createUser($username, $package, $validityDays)
    {
        // Example payload for MikroTik API
        $payload = [
            'username' => $username,
            'package' => $package,
            'validity' => $validityDays . 'd',
        ];

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post($this->baseUrl . '/user/create', [
                'auth' => [$this->auth['username'], $this->auth['password']],
                'json' => $payload,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('MikroTik Error: ' . $e->getMessage());
            return null;
        }
    }
}
