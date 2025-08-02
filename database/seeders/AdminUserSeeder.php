<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // نُنشئ المستخدم الإداري أو نستدعيه إذا كان موجودًا
        $user = User::firstOrCreate(
            ['email' => 'admin@muftah.com'],
            [
                'name'              => 'المدير العام',
                'password'          => Hash::make('secret123'), // غيّر كلمة المرور كما تريد
                'is_active'         => true,
                'point_of_sale_id'  => null,                   // لا ينطبق على المدير العام
                                 'role' => 'admin'
            ]
        );

        // نتأكد من أنه فعليًا يحمل دور 'admin'
        if (! $user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}
