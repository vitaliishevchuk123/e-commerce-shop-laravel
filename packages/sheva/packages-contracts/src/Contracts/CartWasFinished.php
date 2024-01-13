<?php

namespace Sheva\PackagesContracts\Contracts;

interface CartWasFinished
{
    public function getCart(): FinishedCart;

    public function getType(): int;
}
