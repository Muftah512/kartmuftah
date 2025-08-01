<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PointOfSale;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * خريطة الـ Policies.
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        // سجّل الـ Policies أولاً
        $this->registerPolicies();

                Gate::define('manage_pos', fn($user) => $user->hasRole('admin')) || $user->id === $pos->accountant_id;

        //––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––
        // بوّابات الوصول (Gates) لإدارة نقاط البيع
        Gate::define('view-any-pos', function ($user) {
            return $user->hasRole('admin') ||
                       $user->id === $pos->accountant_id;
        });
        Gate::define('view-pos', function ($user, PointOfSale $pos) {
    return $user->hasRole('admin') || $user->id === $pos->accountant_id;
});  
        Gate::define('create-pos', function ($user) {
            return $user->hasRole('admin') || $user->id === $pos->accountant_id;
        });
        Gate::define('update-pos', function ($user, PointOfSale $pos) {
            return $user->hasRole('admin') || $user->id === $pos->accountant_id;
        });
        Gate::define('delete-pos', function ($user, PointOfSale $pos) {
            return $user->hasRole('admin') || $user->id === $pos->accountant_id;
        });
        //––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––

        // تهيئة الأدوار والصلاحيات (إذا تبي تخصيص تلقائي من config/permissions.php)
        if (! $this->app->runningInConsole() && Schema::hasTable('roles')) {
            $permissionsConfig = config('permissions', []);
            foreach ($permissionsConfig as $roleName => $perms) {
                $role = Role::firstOrCreate(['name' => $roleName]);
                foreach ($perms as $permissionName) {
                    Permission::firstOrCreate([
                        'name'       => $permissionName,
                        'guard_name' => 'web',
                    ]);
                }
                $role->syncPermissions($perms);
            }
        }
    }
}
