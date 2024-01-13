<?php

namespace Sheva\PackagesContracts\Contracts;

use Brick\Money\Money;

interface CartProduct extends SimpleCartProduct, WithBuyPrices, WithSitePrices, WithQuantity, DiscountsProducts
{
    public function getSitePrice(): Money;

    public function getTotalSitePrice(): Money;

    public function getSiteDeliveryPrice(): Money;

    public function getSiteDiscountPrice(): Money;

    public function getSiteWithoutDiscountPrice(): Money;
}
