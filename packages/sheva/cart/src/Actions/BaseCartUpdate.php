<?php

namespace Sheva\Cart\Actions;

use Sheva\Cart\Facades\Cart;

abstract class BaseCartUpdate
{
    abstract public function handle(array $data);

    /**
     * Refreshing cart lifetime on every user modification with it
     */
    protected function refreshCartModel(): \Sheva\Cart\Models\Cart
    {
        $cart = Cart::getCartModel();
        $cart->cart_at = now()->addHours(config('cart.cart_lifetime_hours'));
        $cart->save();

        return $cart;
    }
}
