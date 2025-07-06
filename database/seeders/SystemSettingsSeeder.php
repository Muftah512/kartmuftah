<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    public function run()
    {


DB::table('system_settings')->updateOrInsert(
    ['key' => 'whatsapp_api_key'],
    [
        'value' => 'your-api-key-here',
        'updated_at' => now(),
        'created_at' => now()
    ]
);

    }
}