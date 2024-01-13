<?php

namespace Sheva\PackagesContracts\Contracts;

interface DiscountsProducts
{
    public function getMinorDiscountPrice(): int;

    public function getMinorWithoutDiscountPrice(): int;
}
