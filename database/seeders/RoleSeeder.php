<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
{
        // خريطة الأدوار والصلاحيات
        $map = [
            'admin'      => ['create cards', 'view reports', 'manage users'],
            'accountant' => ['view reports'],
            'pos'        => ['create cards'],
        ];

        foreach ($map as $roleName => $perms) {
Permission::firstOrCreate(['name' => 'pos.create']);
Permission::firstOrCreate(['name' => 'pos.topup']);
Permission::firstOrCreate(['name' => 'pos.view-own']);
Permission::firstOrCreate(['name' => 'report.view-own']);

// ربط الأذونات بدور المحاسب
$accountant = Role::firstOrCreate(['name' => 'accountant']);
$accountant->syncPermissions([
'pos.create',
'pos.topup',
'pos.view-own',
'report.view-own',
]);
            // إنشاء الدور إذا لم يكن موجودًا
            $role = Role::firstOrCreate([
                'name'       => $roleName,
                'guard_name' => 'web'
            ]);

            // مزامنة الصلاحيات
            $role->syncPermissions($perms);
        }
    }
}
