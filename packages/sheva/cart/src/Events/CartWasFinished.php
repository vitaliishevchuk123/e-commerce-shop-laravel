<?php

namespace Sheva\Cart\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Sheva\PackagesContracts\Contracts\CartsWasFinished;
use Sheva\PackagesContracts\Contracts\FinishedCart;

class CartWasFinished implements CartsWasFinished
{
    use Dispatchable;

    protected FinishedCart $cart;
    protected int $type;

    public function __construct(FinishedCart $cart, int $type)
    {
        $this->cart = $cart;
        $this->type = $type;
    }

    public function getCart(): FinishedCart
    {
        return $this->cart;
    }

    public function getType(): int
    {
        return $this->type;
    }
}
