<?php
 
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@muftah.com'],
            [
                'name' => 'المدير العام',
                'password' => Hash::make('secret123'),  // غيّر كلمة المرور كما تريد
                'is_active' => 1,
                // حقل point_of_sale_id إن كان مطلوبًا:
                'point_of_sale_id' => null,
            ]
        );
    }
}
