<?php

namespace Sheva\Cart\Actions;

use Sheva\Cart\Facades\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Sheva\PackagesContracts\Contracts\CartsProducts;
use Sheva\PackagesContracts\Contracts\ProductRepository;

class ChangeProductQuantityInCart extends BaseCartUpdate
{
    public function handle(array $data): CartsProducts
    {
        $validated = Validator::make($data, [
            'slug' => ['required', 'string',],
            'quantity' => ['required', 'numeric', 'min:1',],
        ])->validate();

        $product = app(ProductRepository::class)
            ->getProductBySlug($validated['slug']);

        if ($product->getAvailableQuantity() < $validated['quantity']) {
            throw ValidationException::withMessages(['quantity' => 'The quantity is invalid']);
        }

        if (!Cart::hasProduct($validated['slug'])) {
            throw ValidationException::withMessages(['product' => 'The product not found']);
        }

        $cart = $this->refreshCartModel();

        $status = $cart->cartProducts()
            ->where('slug', '=', $validated['slug'])
            ->update([
                'quantity' => $validated['quantity'],
                'available_quantity' => $product->getAvailableQuantity(),
            ]);

        if ($status === 0){
            throw ValidationException::withMessages(['product' => 'The product quantity not updated!']);
        }

        Cart::fresh();

        return $cart->cartProducts()
            ->where('slug', '=', $validated['slug'])
            ->firstOrFail();
    }
}
