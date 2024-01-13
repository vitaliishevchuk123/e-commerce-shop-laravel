<?php

namespace Sheva\Cart\Actions;

use Sheva\Cart\Facades\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Sheva\PackagesContracts\Contracts\CartProduct;
use Sheva\PackagesContracts\Contracts\DiscountsProducts;
use Sheva\PackagesContracts\Contracts\ProductRepository;

class AddProductToCart extends BaseCartUpdate
{
    public function handle(array $data): CartProduct
    {
        $validated = Validator::make($data, [
            'slug' => ['required', 'string',],
            'quantity' => ['required', 'numeric', 'min:1',],
        ])->validate();

        $product = app(ProductRepository::class)
            ->getProductBySlug($validated['slug'], $validated['quantity']);

        if ($product->getAvailableQuantity() < $validated['quantity']) {
            throw ValidationException::withMessages(['quantity' => __('The quantity is invalid')]);
        }

        if (Cart::hasProduct($product->getSlug())){
            throw ValidationException::withMessages(['product' => __('The product already exists')]);
        }

        $cart = $this->refreshCartModel();
        $orderProduct = $cart->cartProducts()
            ->create([
                'slug' => $product->getSlug(),
                'sku' => $product->getSku(),
                'product_type' => $product->getProductType(),

                'quantity' => $validated['quantity'],
                'available_quantity' => $product->getAvailableQuantity(),

                'name' => $product->getName(),
                'image' => $product->getImage()->size(['w:720']),

                'buy_price' => $product->getMinorBuyPrice(),
                'buy_delivery_price' => $product->getMinorBuyDeliveryPrice(),
                'buy_url' => $product->getBuyUrl(),

                'site_price' => $product->getMinorSitePrice(),
                'site_delivery_price' => $product->getMinorSiteDeliveryPrice(),

                'site_discount' => $product instanceof DiscountsProducts ? $product->getMinorDiscountPrice() : 0,
            ]);

        Cart::fresh();

        return $orderProduct;
    }
}
