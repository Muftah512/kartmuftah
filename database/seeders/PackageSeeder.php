<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
              'name'             => 'باقة 500 ميجا',
              'price'            => 200,
              'size_mb'          => 500,
              'validity_days'    => 2,
              'mikrotik_profile' => '500MB-Profile',
            ],
            // بقية الباقات...
        ];

        foreach ($packages as $data) {
            Package::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
