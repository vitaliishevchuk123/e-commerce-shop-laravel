<?php

namespace Sheva\Cart;

use Brick\Money\Money;
use Illuminate\Support\Collection;
use Sheva\Cart\Contracts\CartsStorage;
use Sheva\Cart\Helpers\CartTotalPrice;
use Sheva\Cart\Helpers\DeliveryPrice;
use Sheva\Cart\Helpers\ProductsTotalDiscountPrice;
use Sheva\Cart\Helpers\ProductsTotalPrice;
use Sheva\PackagesContracts\Contracts\CartProduct;

class Cart
{
    protected Collection $cartProducts;
    protected string $siteCurrency;
    protected string $userUuid;
    protected \Sheva\Cart\Models\Cart $cart;

    public function __construct(string $siteCurrency)
    {
        $this->siteCurrency = $siteCurrency;
        $this->userUuid = app(CartsStorage::class)->getUserUuid();
    }

    public function getTotalPrice(): Money
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        return app(CartTotalPrice::class)
            ->handle($this->getCartModel(), $this->siteCurrency);
    }

    /**
     * @throws \Exception
     */
    public function getProductsTotalPrice(): Money
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        return app(ProductsTotalPrice::class)
            ->handle($this->getCartModel(), $this->siteCurrency);
    }

    /**
     * The entire amount of discounts
     *
     * @throws \Exception
     */
    public function getProductsDiscountTotalPrice(): Money
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        return app(ProductsTotalDiscountPrice::class)
            ->handle($this->getCartModel(), $this->siteCurrency);
    }

    /**
     * @throws \Exception
     */
    public function getDeliveryPrice(): Money
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        return app(DeliveryPrice::class)
            ->handle($this->cart, $this->siteCurrency);
    }

    /**
     * Goods in the basket
     */
    public function cartProducts(): Collection
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        return $this->cartProducts;
    }

    /**
     * Is there a product in the basket?
     */
    public function hasProduct(string $slug): bool
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        return $this->cartProducts->filter(function ($product) use ($slug) {
            return $product->getSlug() === $slug;
        })->isNotEmpty();
    }

    public function getProductQuantity(string $slug): ?int
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        $product = $this->getProductBySlug($slug);

        return optional($product)->getQuantity();
    }

    public function getProductBySlug(string $slug): ?CartProduct
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        return $this->cartProducts->firstWhere('slug', '=', $slug);
    }

    /**
     * The number of products in the basket according to the list
     */
    public function getProductsListCount(): int
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        return $this->cartProducts->count();
    }

    /**
     * Number of products in the basket
     */
    public function getProductsQuantityCount(): int
    {
        if (!isset($this->cartProducts)) {
            $this->initCartProducts();
        }

        return $this->cartProducts->sum->getQuantity();
    }

    /**
     * Initialization of products in the cart
     */
    protected function initCartProducts()
    {
        if (!isset($this->cart)) {
            $this->initCart();
        }

        $this->cartProducts = $this->cart->cartProducts;
    }

    /**
     * Initialize the shopping cart
     */
    protected function initCart(): void
    {
        $this->cart = \Sheva\Cart\Models\Cart::firstOrNew([
            'cart_user_uuid' => $this->userUuid,
        ], [
            'cart_at' => now()->addDays(2),
            'user_id' => auth()->user()?->id,
        ]);
    }

    /**
     * The currency in which the products are presented on the website
     */
    public function getSiteCurrency(): string
    {
        return $this->siteCurrency;
    }

    public function getCartModel(): Models\Cart
    {
        if (!isset($this->cart)) {
            $this->initCart();
        }

        return $this->cart;
    }

    public function getPaymentMethod()
    {
        $cart = $this->getCartModel();

        return app(PaymentManager::class)
            ->driver($cart->cartPayment->method)
            ->setStatus($cart->cartPayment->status);
    }

    /**
     * Fresh cart to avoid old data mapping
     */
    public function fresh(): void
    {
        unset($this->cartProducts);
        unset($this->cart);
    }
}
