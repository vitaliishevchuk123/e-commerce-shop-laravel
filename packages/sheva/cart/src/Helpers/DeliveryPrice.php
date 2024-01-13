<?php

namespace Sheva\Cart\Helpers;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Sheva\Cart\Models\Cart;
use Illuminate\Contracts\Container\BindingResolutionException;

class DeliveryPrice
{
    /**
     * @throws UnknownCurrencyException
     * @throws BindingResolutionException
     */
    public function handle(Cart $cart, string $currency): Money
    {
        if (is_null($cart->cartDelivery->carrier)){
            return Money::ofMinor(0, $currency);
        }

        return app()->make($this->getDeliveryPriceAbstract($cart->cartDelivery->carrier))->handle($cart, $currency);
    }

    public static function getDeliveryPriceAbstract($method): string
    {
        return 'cart.delivery.calculate-' . $method . '-price';
    }
}
