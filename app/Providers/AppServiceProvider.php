<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Di semua view tersedia $s('kunci', 'default') untuk membaca pengaturan toko.
        View::composer('*', function ($view) {
            $view->with('s', fn (string $key, ?string $default = null) => Setting::get($key, $default));
        });

        View::composer('layouts.app', function ($view) {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
