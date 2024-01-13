<?php

namespace App\Repositories;

use App\Helpers\CartProductAdapter;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Sheva\PackagesContracts\Contracts\ProductRepository;
use Sheva\PackagesContracts\Contracts\SimpleCartProduct;

class CartProductRepository implements ProductRepository
{
    public function getProductBySlug(string $slug, int $quantity = 1): SimpleCartProduct
    {
        $product = $this->getProduct($slug);
        if ($product->quantity < $quantity) {
            throw ValidationException::withMessages(['product' => __('Not enough product quantity for adding to cart', ['count' => $product->getQuantity()])]);
        }
        return new CartProductAdapter($product, $quantity);
    }

    public function getProduct(string $slug)
    {
        $product = Product::findBySlug($slug);
        if (!$product) {
            throw ValidationException::withMessages(['product' => __('Cart product not found')]);
        }
        return $product;
    }
}
