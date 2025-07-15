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
        $users = User::role('pos')->get();

        if ($pointsOfSale->isEmpty()) {
            $pointsOfSale = PointOfSale::factory(5)->create();
        }

        if ($users->isEmpty()) {
            $users = User::factory(5)->create()->each(function ($user) {
                $user->assignRole('pos');
            });
        }

        foreach ($pointsOfSale as $pos) {
            $balance = rand(1000, 3000); // رصيد مبدئي لكل نقطة بيع

            // معاملة شحن مبدئية
            $creditAmount = rand(500, 2000);
            $balance += $creditAmount;

            Transaction::create([
                'type'          => 'credit',
                'amount'        => $creditAmount,
                'pos_id'        => $pos->id,
                'user_id'       => $users->random()->id,
                'description'   => 'شحن رصيد نقطة البيع',
                'balance_after' => $balance,
            ]);

            // تحديث الرصيد في جدول نقطة البيع
            $pos->balance = $balance;
            $pos->save();

            // معاملات خصم (بيع كروت)
            for ($i = 0; $i < 5; $i++) {
                $debitAmount = rand(20, 100);
                $balance -= $debitAmount;

                Transaction::create([
                    'type'          => 'debit',
                    'amount'        => $debitAmount,
                    'pos_id'        => $pos->id,
                    'user_id'       => $users->random()->id,
                    'description'   => 'بيع كرت إنترنت رقم ' . ($i + 1),
                    'balance_after' => $balance,
                ]);

                // تحديث الرصيد الحالي بعد كل خصم
                $pos->balance = $balance;
                $pos->save();
            }
        }

        $this->command->info('تم إنشاء ' . Transaction::count() . ' معاملة بنجاح');
    }
}
