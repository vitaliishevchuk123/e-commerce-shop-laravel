<?php

namespace Sheva\Cart\Actions;

use Sheva\Cart\Contracts\CartsStorage;
use Sheva\Cart\Facades\Cart;
use Illuminate\Support\Str;

class StoreOneClick extends BaseCartUpdate
{
    /**
     * @throws \Exception
     */
    public function handle(array $data): \Sheva\Cart\Models\Cart
    {
        $stashUserUuid = app(CartsStorage::class)->getUserUuid();
        app(CartsStorage::class)->setUserUuid(Str::uuid()->toString());

        app(AddProductToCart::class)->handle($data);

        app(UpdateClientInformation::class)->handle($data);

        app(FinishCart::class)->handle($data);

        app(CartsStorage::class)->setUserUuid($stashUserUuid);

        return Cart::getCartModel();
    }
}
