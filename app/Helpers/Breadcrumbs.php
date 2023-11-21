<?php

namespace App\Helpers;

class Breadcrumbs
{
    private array $crumbs = [];

    public function add(string $title, ?string $url = 'javascript:void(0);'): static
    {
        $this->crumbs[] = [
            'title' => $title,
            'url' => $url,
        ];
        return $this;
    }

    public function crumbs(): array
    {
        return $this->crumbs;
    }
}
