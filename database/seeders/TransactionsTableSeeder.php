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
        $pointsOfSale = PointOfSale::all();
        $users = User::where('role', 'pos')->get();

        // إذا لم تكن هناك نقاط بيع أو مستخدمين، أنشئ بعضها أولاً
        if ($pointsOfSale->isEmpty()) {
            $pointsOfSale = PointOfSale::factory(5)->create();
        }
        
        if ($users->isEmpty()) {
            $users = User::factory(5)->create(['role' => 'pos']);
        }

        // إنشاء معاملات لكل نقطة بيع
        foreach ($pointsOfSale as $pos) {
            // معاملات خصم (بيع كروت)
            for ($i = 0; $i < 5; $i++) {
                Transaction::create([
                    'type' => 'debit',
                    'amount' => rand(20, 100),
                    'pos_id' => $pos->id,
                    'user_id' => $users->random()->id,
                    'description' => 'بيع كرت إنترنت رقم ' . ($i + 1),
                ]);
            }
            
            // معاملات شحن (شحن رصيد)
            Transaction::create([
                'type' => 'credit',
                'amount' => rand(500, 2000),
                'pos_id' => $pos->id,
                'user_id' => $users->random()->id,
                'description' => 'شحن رصيد نقطة البيع',
            ]);
        }

        $this->command->info('تم إنشاء ' . Transaction::count() . ' معاملة بنجاح');
    }
}