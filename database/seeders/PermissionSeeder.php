<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_dashboard',
            'manage_admins',
            // ÃÖÝ åäÇ ÈÇÞí ÇáÕáÇÍíÇÊ ÇáÊí íÍÊÇÌåÇ ÊØÈíÞß
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name'       => $perm,
                'guard_name' => 'web',
            ]);
        }
    }
}
