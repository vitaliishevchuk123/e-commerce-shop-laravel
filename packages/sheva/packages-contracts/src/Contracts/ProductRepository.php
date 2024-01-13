<?php

namespace Sheva\PackagesContracts\Contracts;

interface ProductRepository
{
    /**
     * Getting the product
     *
     * @return WithSitePrices|WithBuyPrices|SimpleCartProduct
     */
    public function getProductBySlug(string $slug, int $quantity = 1): SimpleCartProduct;
}
