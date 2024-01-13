<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Sheva\Cart\Http\Controllers\CartClientInfoController;
use Sheva\Cart\Http\Controllers\CartController;
use Sheva\Cart\Http\Controllers\CartDeliveryController;
use Sheva\Cart\Http\Controllers\CartPaymentController;
use Sheva\Cart\Http\Controllers\CartProductController;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['web', 'localizationRedirect'],
], static function () {

    /** Cart*/
    Route::controller(CartController::class)->group(function () {
        /** Checkout page */
        Route::get('cart/', 'index')
            ->name('cart.index');
        /** View page of the completed order */
        Route::get('cart/success/', 'show')
            ->name('cart.show');
        /** Save order */
        Route::post('cart/', 'store')
            ->name('cart.store');
        /** Order in one click */
        Route::post('cart/one-click/', 'storeOneClick')
            ->name('cart.one-click.store');
    });

    /** Product manipulation */
    Route::controller(CartProductController::class)->group(function () {
        Route::post('cart/products/', 'store')
            ->name('cart.products.store');
        Route::patch('cart/products/', 'update')
            ->name('cart.products.update');
        Route::delete('cart/products/', 'destroy')
            ->name('cart.products.destroy');
    });

    /** Оновлення інформації про клієнта */
    Route::patch('cart/client/', [CartClientInfoController::class, 'update'])
        ->name('cart.client.update');

    /** Оновлення інформації про доставку */
    Route::patch('cart/deliveries/', [CartDeliveryController::class, 'update'])
        ->name('cart.deliveries.update');
    Route::post('cart/deliveries/calculate/', [CartDeliveryController::class, 'calculate'])
        ->name('cart.deliveries.calculate');

    /** Оновлення інформації про оплату */
    Route::patch('cart/payments/', [CartPaymentController::class, 'update'])
        ->name('cart.payments.update');
    Route::post('cart/payments/{method}/callback/', [CartPaymentController::class, 'callback'])
        ->name('cart.payments.callback');
});
