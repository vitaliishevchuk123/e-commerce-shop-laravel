<?php

namespace Sheva\PackagesContracts\Contracts;

interface WithBuyPrices
{
    /**
     * The currency of purchase goods
     */
    public function getCurrencyBuyPrice(): string;

    /**
     * Purchase price in kopecks
     */
    public function getMinorBuyPrice(): int;

    /**
     * Purchase price of delivery in kopecks
     */
    public function getMinorBuyDeliveryPrice(): int;

    /**
     * Link for purchasing goods
     */
    public function getBuyUrl(): string;
}
