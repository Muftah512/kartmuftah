<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // تعريف الأذونات باستخدام Gates
        Gate::define('view-reports', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('export-cards', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('update-pos', function ($user, $pos) {
            return $user->hasRole('admin');
        });

        Gate::define('delete-pos', function ($user, $pos) {
            return $user->hasRole('admin');
        });

        // أيّ إنشاء أو مزامنة للأدوار والصلاحيات يجب أن يتم فقط عبر الـ Seeders
    }
}