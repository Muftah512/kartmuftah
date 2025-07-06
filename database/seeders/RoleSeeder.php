<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ÇÞÑÃ ãÕÝæÝÉ ÇáÃÏæÇÑ æÇáÕáÇÍíÇÊ ãä config/permissions.php
        $map = config('permissions', [
            'admin'      => ['create cards', 'view reports', 'manage users'],
            'supervisor' => ['create cards', 'view reports'],
            'accountant' => ['view reports'],
            'vendor'     => ['create cards'],
        ]);

        foreach ($map as $roleName => $perms) {
            // ÃäÔÆ ÇáÏæÑ ÅÐÇ áã íßä ãæÌæÏÇð
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );

            // ÊÃßÏ Ãä ÇáÕáÇÍíÇÊ Ýí ãÕÝæÝÉ ãÓØøÍÉ
            if (! is_array($perms)) {
                // ÅÐÇ æÌÏäÇ ÈÏá ãÕÝæÝÉ ÞíãÉ æÇÍÏÉ¡ ÛíøÑåÇ Åáì ãÕÝæÝÉ
                $perms = [$perms];
            }

            // ÃäÔÆ ßá ÕáÇÍíøÉ Ëã ÇÌãÚåÇ Ýí ÞÇÆãÉ äÕíÉ
            $permNames = [];
            foreach ($perms as $perm) {
                if (is_string($perm)) {
                    Permission::firstOrCreate(
                        ['name' => $perm],
                        ['guard_name' => 'web']
                    );
                    $permNames[] = $perm;
                }
            }

            // ãÒÇãäøÉ ÇáÕáÇÍíÇÊ ãÚ ÇáÏæÑ ÈäÕæÕ ÇáÃÓãÇÁ
            $role->syncPermissions($permNames);
        }
    }
}
