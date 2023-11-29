<?php

namespace App\Repositories;

use App\Helpers\Breadcrumbs;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CatalogRepository
{
    public function categoryProducts(): LengthAwarePaginator;

    public function filters();

    public function breadcrumbs(): Breadcrumbs;

    public function category(): Category;

    public function getChildrenOrSiblingsAndSelfCats(): Collection;

    public function perPage(): int;
}
