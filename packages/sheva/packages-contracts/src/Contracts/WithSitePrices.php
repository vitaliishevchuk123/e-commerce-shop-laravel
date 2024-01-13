<?php

namespace Sheva\PackagesContracts\Contracts;

interface WithSitePrices
{
    /**
     * Валюта товарів на сайті
     */
    public function getCurrencySitePrice(): string;

    /**
     * Продажна ціна в копійках
     */
    public function getMinorSitePrice(): int;

    /**
     * Доставка товару на сайті в копійках
     */
    public function getMinorSiteDeliveryPrice(): int;

    /**
     * Лінк на товар
     */
    public function getSiteUrl(): string;
}
