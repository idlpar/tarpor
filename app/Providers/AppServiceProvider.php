<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

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
//        $roles = ['super', 'admin', 'user', 'guest'];
//        foreach ($roles as $role) {
//            Role::firstOrCreate(['name' => $role]);
//        }
    }
}
