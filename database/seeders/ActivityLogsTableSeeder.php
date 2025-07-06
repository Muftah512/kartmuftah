<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\PointOfSale;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $pointsOfSale = PointOfSale::all();

        foreach ($users as $user) {
            ActivityLog::create([
                'action' => 'login',
                'description' => 'قام المستخدم بتسجيل الدخول',
                'user_id' => $user->id,
                'pos_id' => $user->pos_id,
            ]);

            ActivityLog::create([
                'action' => 'operation',
                'description' => 'قام المستخدم بإجراء عملية',
                'user_id' => $user->id,
                'pos_id' => $user->pos_id,
            ]);
        }
    }
}