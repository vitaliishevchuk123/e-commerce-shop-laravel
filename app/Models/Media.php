<?php

namespace App\Models;

use Sheva\PackagesContracts\Contracts\ImagesResizes;

class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media implements ImagesResizes
{
    /**
     * Setting $options and $extension can be implemented later
     * @param array $options
     * @param $extension
     * @return string
     */
    public function size(array $options = [], ?string $extension = 'webp'): string
    {
        return $this->getUrl();
    }
}
