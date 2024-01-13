<?php

namespace Sheva\Cart\Actions;

use Sheva\Cart\Facades\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RemoveProductFromCart extends BaseCartUpdate
{
    public function handle(array $data): bool
    {
        $validated = Validator::make($data, [
            'slug' => ['required', 'string',],
        ])->validate();

        if (!Cart::hasProduct($validated['slug'])) {
            throw ValidationException::withMessages(['product' => 'The product not found']);
        }

        $cart = $this->refreshCartModel();

        $success = $cart->cartProducts()
            ->where('slug', '=', $validated['slug'])
            ->delete();

        Cart::fresh();

        return (bool)$success;
    }
}
