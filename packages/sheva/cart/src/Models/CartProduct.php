<?php

namespace Sheva\Cart\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Sheva\PackagesContracts\Contracts;
use Sheva\PackagesContracts\Contracts\ImagesResizes;
use Spatie\Translatable\HasTranslations;

class CartProduct extends Model implements Contracts\CartProduct
{
    use HasTranslations;

    public array $translatable = ['name',];

    protected $fillable = [
        'cart_id',

        'slug',
        'sku',
        'product_type',

        'quantity',
        'available_quantity',

        'name',
        'image',

        'buy_price',
        'buy_delivery_price',
        'buy_url',

        'site_price',
        'site_delivery_price',
        'site_discount',
    ];

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSiteUrl(): string
    {
        return $this->site_url;
    }

    public function getWeightCharName(): string
    {
        return $this->weight_char_name;
    }

    public function getWeightCharValue(): string
    {
        return $this->weight_char_value;
    }

    public function getModCharName(): string
    {
        return $this->mod_char_name;
    }

    public function getModCharValue(): string
    {
        return $this->mod_char_value;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getCurrencySitePrice(): string
    {
        return 'UAH';
    }

    public function getMinorSitePrice(): int
    {
        return $this->site_price;
    }

    public function getMinorSiteDeliveryPrice(): int
    {
        return $this->site_delivery_price;
    }

    public function getSku(): ?string
    {
        return $this->slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): ImagesResizes
    {
        return new class($this->image) implements ImagesResizes {
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
        return $this->available_quantity;
    }

    public function getProductType(): string
    {
        return $this->product_type;
    }

    public function getSitePrice(): Money
    {
        return Money::ofMinor($this->getMinorSitePrice(), $this->getCurrencySitePrice());
    }

    public function getTotalSitePrice(): Money
    {
        return $this->getSitePrice()->multipliedBy($this->getQuantity());
    }

    public function getTotalSiteWithoutDiscountPrice(): Money
    {
        return $this->getSiteWithoutDiscountPrice()->multipliedBy($this->getQuantity());
    }

    public function getSiteDeliveryPrice(): Money
    {
        return Money::ofMinor($this->getMinorSiteDeliveryPrice(), $this->getCurrencySitePrice());
    }

    public function getSiteDiscountPrice(): Money
    {
        return Money::ofMinor($this->getMinorDiscountPrice(), $this->getCurrencySitePrice());
    }

    public function getMinorDiscountPrice(): int
    {
        return $this->site_discount;
    }

    public function getMinorWithoutDiscountPrice(): int
    {
        return $this->site_price + $this->site_discount;
    }

    public function getSiteWithoutDiscountPrice(): Money
    {
        return Money::ofMinor($this->getMinorWithoutDiscountPrice(), $this->getCurrencySitePrice());
    }

    public function getCurrencyBuyPrice(): string
    {
        return 'UAH';
    }

    public function getMinorBuyPrice(): int
    {
        return $this->buy_price;
    }

    public function getMinorBuyDeliveryPrice(): int
    {
        return $this->buy_delivery_price;
    }

    public function getMinorSiteTotalPrice(): int
    {
        return $this->getMinorSitePrice() + $this->getMinorSiteDeliveryPrice();
    }

    public function getSiteTotalPrice(): Money
    {
        return Money::ofMinor($this->getMinorSiteTotalPrice(), $this->getCurrencySitePrice());
    }

    public function getBuyUrl(): string
    {
        return $this->buy_url;
    }
}
