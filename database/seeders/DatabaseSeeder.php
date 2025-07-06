<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call seeders in proper order
         $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminUserSeeder::class,
            UsersTableSeeder::class,
            PointOfSalesTableSeeder::class,
            PackageSeeder::class,
            InternetCardsTableSeeder::class,
            TransactionsTableSeeder::class,
            ActivityLogsTableSeeder::class,
            SystemSettingsSeeder::class,
        ]);
    }
}
