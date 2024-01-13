<?php

namespace Sheva\PackagesContracts\Contracts;

interface SimpleCartProduct
{
    /**
     * Unique product identifier
     */
    public function getSlug(): string;

    /**
     * Alternative product identifier - SKU number
     */
    public function getSku(): ?string;

    /**
     * Product name
     */
    public function getName(): string;

    /**
     * Product photo
     */
    public function getImage(): ImagesResizes;

    /**
     * Maximum available number of products
     */
    public function getAvailableQuantity(): int;

    /**
     * Type of product being sold
     */
    public function getProductType(): string;
}
