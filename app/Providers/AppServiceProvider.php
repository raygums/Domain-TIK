<?php

namespace App\Providers;

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
        // Force HTTPS in production
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        // Set PostgreSQL timeouts for production
        // Only when running in web/console context (not during composer install)
        if (config('database.default') === 'pgsql' && !app()->runningUnitTests()) {
            try {
                \Illuminate\Support\Facades\DB::statement("SET lock_timeout = '30s'");
                \Illuminate\Support\Facades\DB::statement("SET statement_timeout = '60s'");
            } catch (\Exception $e) {
                // Database not ready yet (e.g., during build), skip silently
            }
        }
    }
}
