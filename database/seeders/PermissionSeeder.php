<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // قائمة الصلاحيات الأساسية في النظام
        $permissions = [
            'create cards',    // إنشاء بطاقات الإنترنت في نقطة البيع
            'view reports',    // مشاهدة التقارير المالية والبيعية
            'manage users',    // إدارة المستخدمين (إنشاء وتعديل وحذف)
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name'       => $perm,
                'guard_name' => 'web',
            ]);
        }
    }
}
