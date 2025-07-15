<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PointOfSale;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // إذا لم يكن هناك نقط بيع اصلاً، ننشئ واحدة افتراضياً
        $defaultPos = PointOfSale::first() ?: PointOfSale::factory()->create();

        $users = [
            [
                'name'              => 'Admin User',
                'email'             => 'admin@cardmuftah.com',
                'password'          => 'password',
                'is_active'         => true,
                'role'              => 'admin',
                'point_of_sale_id'  => null,
            ],
            [
                'name'              => 'Accountant User',
                'email'             => 'accountant@cardmuftah.com',
                'password'          => 'password',
                'is_active'         => true,
                'role'              => 'accountant',
                'point_of_sale_id'  => null,
            ],
            [
                'name'              => 'POS User',
                'email'             => 'pos@cardmuftah.com',
                'password'          => 'password',
                'is_active'         => true,
                'role'              => 'pos',
                // إذا كانت النقطة 1 غير موجودة نستخدم $defaultPos
                'point_of_sale_id'  => PointOfSale::find(1)?->id ?? $defaultPos->id,
            ],
        ];

        foreach ($users as $data) {
            // نحتفظ بالدور ثم نحذفه من المصفوفة
            $role = $data['role'];
            unset($data['role']);

            // نبني بيانات الحقل password مُشفرة
            $data['password'] = Hash::make($data['password']);

            // ننشئ أو نعيد نموذج المستخدم بناءً على الـ email
            $user = User::firstOrNew(['email' => $data['email']]);
            $user->fill([
                'name'             => $data['name'],
                'password'         => $data['password'],
                'is_active'        => $data['is_active'],
                'point_of_sale_id' => $data['point_of_sale_id'],
            ])->save();

            // نسنّد الدور (سيمسح أي أدوار سابقة ويضع هذا فقط)
            $user->syncRoles([$role]);
        }
    }
}
