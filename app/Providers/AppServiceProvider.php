<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Carrito;
use Illuminate\Support\Facades\DB;

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
        View::composer('layouts.app', function ($view) {
            $count = 0;
            $cartId = session('cart_id');
            if ($cartId && ($carrito = Carrito::withCount('items')->find($cartId))) {
                // suma de cantidades reales:
                $count = $carrito->items()->sum('cantidad');
            }
            $view->with('cartCount', $count);
        });
    }
}
