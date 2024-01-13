<?php

namespace App\Helpers;

use App\Models\Product;
use Sheva\PackagesContracts\Contracts\ImagesResizes;
use Sheva\PackagesContracts\Contracts\SimpleCartProduct;

class CartProductAdapter implements SimpleCartProduct
{
    protected Product $product;
    protected int $quantity = 1;

    public function __construct(Product $product, int $quantity = 1)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getSlug(): string
    {
        return $this->product->slug;
    }

    public function getSku(): ?string
    {
        return $this->product->sku;
    }

    public function getName(): string
    {
        return $this->product->name;
    }

    public function getImage(): ImagesResizes
    {
        return $this->product->getFirstMedia() ?? new class(config('cart.default_img')) implements ImagesResizes {
            public function __construct(?string $image = null)
            {
                $this->image = $image;
            }

            public function size(array $options = [], $extension = 'webp'): string
            {
                return $this->image;
            }
        };
    }

    public function getAvailableQuantity(): int
    {
        return $this->product->quantity;
    }

    public function getProductType(): string
    {
        return 'own';
    }

    public function getCurrencySitePrice(): string
    {
        return 'UAH';
    }

    public function getMinorSitePrice(): int
    {
        return $this->product->sale_price;
    }

    public function getMinorSiteDeliveryPrice(): int
    {
        return 0;
    }

    public function getSiteUrl(): string
    {
        return $this->product->getUrl();
    }

    public function getCurrencyBuyPrice(): string
    {
        return 'UAH';
    }

    public function getMinorBuyPrice(): int
    {
        return $this->product->sale_price;
    }

    public function getMinorBuyDeliveryPrice(): int
    {
        return 0;
    }

    public function getBuyUrl(): string
    {
        return $this->product->getUrl();
    }

    public function getMinorDiscountPrice(): int
    {
        return $this->getMinorWithoutDiscountPrice() - $this->getMinorSitePrice();
    }

    public function getMinorWithoutDiscountPrice(): int
    {
        $price = $this->product->old_price ?: $this->product->price;
        return $price * 100;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
