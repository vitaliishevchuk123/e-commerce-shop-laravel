<?php

namespace Sheva\Cart;

use Sheva\Cart\Contracts\CartsStorage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cart.php', 'cart');

        $this->app->singleton(CartsStorage::class, function ($app) {
            return new CookieCartStorage($app->make('request')->cookie('uuid'));
        });

        $this->app->singleton(Cart::class, function () {
            return new Cart(config('cart.site_currency'));
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->configureRoutes();
        $this->loadJsonTranslationsFrom(__DIR__ . '/../lang');
    }

    protected function configureRoutes(): void
    {
        Route::group([
            'domain' => config('cart.domain'),
            'prefix' => config('cart.prefix'),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/cart.php');
        });
    }
}
