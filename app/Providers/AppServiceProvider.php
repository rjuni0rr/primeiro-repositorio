<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // gates to admin
        Gate::define('sys-admin', function ($user){
            return $user->role === 'sys-admin';
        });

        // gates to client-admin
        Gate::define('client-admin', function ($user){
            return $user->role === 'client-admin';
        });

        // gates to client-user
        Gate::define('client-user', function ($user){
            return $user->role === 'client-user';
        });

    }
}
