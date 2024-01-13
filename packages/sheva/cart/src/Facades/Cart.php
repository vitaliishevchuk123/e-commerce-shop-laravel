<?php

namespace Sheva\Cart\Facades;

use Brick\Money\Money;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Sheva\PackagesContracts\Contracts\CartProduct;
use Sheva\PackagesContracts\Contracts\Payments;

/**
 * @method \Sheva\Cart\Models\Cart getCartModel
 * @method void fresh()
 * @property Collection $cartProducts
 * @method int getProductsListCount()
 * @method int getProductsQuantityCount()
 * @method Money getProductsTotalPrice()
 * @method Money getDeliveryPrice()
 * @method Money getTotalPrice()
 * @method Money getProductsDiscountTotalPrice()
 * @method Payments getPaymentMethod()
 * @method bool hasProduct(string $slug)
 * @method int|null getProductQuantity(string $slug)
 * @method CartProduct|null getProductBySlug(string $slug)
 *
 * @see \Sheva\Cart\Cart
 */
class Cart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sheva\Cart\Cart::class;
    }
}
