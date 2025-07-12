<?php

namespace Database\Seeders;

use App\Models\PointOfSale;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    public function run()
    {
        // جلب جميع نقاط البيع
        $pointsOfSale = PointOfSale::all();

        // جلب جميع مستخدمي POS عبر الصلاحيات
        $users = User::role('pos')->get();

        // إذا لم تكن هناك نقاط بيع، أنشئ 5 نقاط بيع افتراضية
        if ($pointsOfSale->isEmpty()) {
            $pointsOfSale = PointOfSale::factory(5)->create();
        }
        
        // إذا لم يكن هناك مستخدمي POS، أنشئ 5 وعيّن لهم دور pos
        if ($users->isEmpty()) {
            $users = User::factory(5)->create()->each(function ($user) {
                $user->assignRole('pos');
            });
        }

        // إنشاء معاملات لكل نقطة بيع
        foreach ($pointsOfSale as $pos) {
            // معاملات خصم (بيع كروت)
            for ($i = 0; $i < 5; $i++) {
                Transaction::create([
                    'type'        => 'debit',
                    'amount'      => rand(20, 100),
                    'pos_id'      => $pos->id,
                    'user_id'     => $users->random()->id,
                    'description' => 'بيع كرت إنترنت رقم ' . ($i + 1),
                ]);
            }
            
            // معاملة شحن (شحن رصيد)
            Transaction::create([
                'type'        => 'credit',
                'amount'      => rand(500, 2000),
                'pos_id'      => $pos->id,
                'user_id'     => $users->random()->id,
                'description' => 'شحن رصيد نقطة البيع',
            ]);
        }

        $this->command->info('تم إنشاء ' . Transaction::count() . ' معاملة بنجاح');
    }
}
