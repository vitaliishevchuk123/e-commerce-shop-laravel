<?php

namespace Sheva\Cart\Helpers;

use Brick\Money\Money;
use Sheva\Cart\Facades\Cart as CartFacade;
use Sheva\Cart\Models\Cart;

class CartTotalPrice
{
    public function handle(Cart $cart, string $currency): Money
    {
        return CartFacade::getProductsTotalPrice()
            ->plus(CartFacade::getDeliveryPrice());
    }
}
