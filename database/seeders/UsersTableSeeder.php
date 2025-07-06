<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // إنشاء مدير النظام
        User::create([
            'name' => 'المدير العام',
            'email' => 'admin@cardmuftah.com',
            'password' => Hash::make('admin_password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // إنشاء محاسب
        User::create([
            'name' => 'محاسب النظام',
            'email' => 'accountant@cardmuftah.com',
            'password' => Hash::make('accountant_password'),
            'role' => 'accountant',
            'is_active' => true,
        ]);

        // إنشاء 10 مستخدمين عشوائيين
        User::factory(10)->create();
    }
}