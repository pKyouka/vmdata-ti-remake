<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load helper files
        require_once app_path('Helpers/DateHelper.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure 'role' middleware alias is registered on boot as a defensive measure
        if (!Route::hasMacro('aliasMiddleware')) {
            // aliasMiddleware exists in router; calling directly on Route facade
        }
        Route::aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);
    }
}
