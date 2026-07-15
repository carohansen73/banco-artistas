<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\View\Composers\EventosComposer;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

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
        // Forzar HTTPS en producción para evitar bloqueos CSP y Mixed Content
        if (config('app.env') === 'production' || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
        Paginator::useTailwind();
        View::composer('components.eventos-slider', EventosComposer::class);
    }
}
