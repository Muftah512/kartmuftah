<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\Package;

class MikroTikService
{
    protected $client;

    public function __construct()
    {
        try {
            $config = Config::get('services.mikrotik');

            if (empty($config['host']) || empty($config['username']) || empty($config['password'])) {
                throw new Exception('MikroTik configuration is missing.');
            }

            $port = (int) ($config['port'] ?? 8728);

            $this->client = new Client([
                'host' => $config['host'],
                'user' => $config['username'],
                'pass' => $config['password'],
                'port' => $port,
                'timeout' => 30,
                'ssl' => false,
            ]);

        } catch (Exception $e) {
            Log::error('MikroTik API Connection Error: ' . $e->getMessage());
            throw new Exception("MikroTik API Connection Error: " . $e->getMessage());
        }
    }

    public function createUser(string $username, Package $package)
    {
        try {
            $query = (new Query('/tool/user-manager/user/add'))
                ->equal('name', $username)
                ->equal('customer', Config::get('services.mikrotik.customer_name'))
                ->equal('profile', $package->name)
                ->equal('validity', $package->validity_days . 'd');

            $response = $this->client->query($query)->read();

            if ($this->hasTrap($response)) {
                Log::error('MikroTik API Error: Failed to create user', ['response' => $response]);
                return false;
            }

            Log::info('MikroTik API Success: User created', ['response' => $response]);
            return true;
        } catch (Exception $e) {
            Log::error('MikroTik API Exception: ' . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function rechargeUser(string $username, Package $package): bool
    {
        try {
            $query = (new Query('/tool/user-manager/user/recharge'))
                ->equal('name', $username)
                ->equal('profile', $package->name)
                ->equal('validity', $package->validity_days . 'd');

            $response = $this->client->query($query)->read();

            if ($this->hasTrap($response)) {
                Log::error('MikroTik API Error: Failed to recharge user', ['response' => $response]);
                return false;
            }

            Log::info('MikroTik API Success: User recharged', ['response' => $response]);
            return true;
        } catch (Exception $e) {
            Log::error('MikroTik API Exception: ' . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function getProfiles(): array
    {
        try {
            $query = new Query('/tool/user-manager/profile/print');
            $profiles = $this->client->query($query)->read();

            if ($this->hasTrap($profiles)) {
                Log::error('MikroTik API Error: Failed to fetch profiles', ['response' => $profiles]);
                return [];
            }

            return array_map(function ($profile) {
                return [
                    'id' => $profile['.id'] ?? null,
                    'name' => $profile['name'] ?? '',
                    'validity' => $profile['validity'] ?? '',
                ];
            }, $profiles);

        } catch (Exception $e) {
            Log::error('MikroTik API Exception (getProfiles): ' . $e->getMessage(), ['exception' => $e]);
            return [];
        }
    }

    protected function hasTrap(array $response): bool
    {
        foreach ($response as $item) {
            if (isset($item['!trap'])) {
                return true;
            }
        }
        return false;
    }
}
