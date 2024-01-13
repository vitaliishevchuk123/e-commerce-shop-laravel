<?php

namespace Sheva\PackagesContracts\Contracts;

use Sheva\PackagesContracts\Contracts\FinishedCart;

interface WithWidget
{
    public function getWidgetData(FinishedCart $cart): array;
}
