<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
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
    // Evita erro de key length no PostgreSQL
        Schema::defaultStringLength(191);

    // Rodar migrations automaticamente em produção
        if (app()->environment('production')) {
            try {
                Artisan::call('migrate', [
                    '--force' => true
                ]);
            } catch (\Throwable $e) {
            // silencioso para não derrubar o app
            }
        }
        if (app()->environment('production')) {
            if (!file_exists(public_path('storage'))) {
                try {
                    Artisan::call('storage:link');
                } catch (\Throwable $e) {}
            }
        }
        if (app()->environment('production')) {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
        }
    }
}
