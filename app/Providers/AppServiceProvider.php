<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        Vite::prefetch(concurrency: 3);

        // Share cart count with all Inertia views
        Inertia::share([
            'cartCount' => function () {
                return app(CartService::class)->getCount();
            },
        ]);
    }
}
