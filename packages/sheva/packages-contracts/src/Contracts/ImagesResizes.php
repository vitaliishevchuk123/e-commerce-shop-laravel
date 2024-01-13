<?php

namespace Sheva\PackagesContracts\Contracts;

interface ImagesResizes
{
    public function size(array $options = [], ?string $extension = 'webp'): string;
}
