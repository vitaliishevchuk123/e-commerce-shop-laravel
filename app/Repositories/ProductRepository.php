<?php

namespace App\Repositories;

use App\Helpers\Breadcrumbs;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductRepository
{
    private ?Product $product;

    public function __construct(private Breadcrumbs $breadcrumbs, private Request $request)
    {
    }

    public function getProduct(?string $slug = null): ?Product
    {
        $slug = $slug ?: $this->request->product;
        if (!$slug) {
            abort(404);
        }
        if (isset($this->product) && $slug === $this->product->slug) {
            return $this->product;
        }
        $this->product = Product::findBySlug($slug);
        if (!$this->product) {
            abort(404);
        }
        $this->product->load(['media', 'categories', 'attributeValues', 'attributeValues.attribute']);
        return $this->product;
    }

    public function breadcrumbs(): Breadcrumbs
    {
        if ($this->breadcrumbs->crumbs()->isNotEmpty()) {
            return $this->breadcrumbs;
        }

        $category = $this->getProduct()->categories->first();
        $category?->parents(0)->map(function (Category $category) {
            $this->breadcrumbs->add($category->name, $category->getCatalogUrl());
        });
        $this->breadcrumbs->add($category->name, $category->getCatalogUrl());
        $this->breadcrumbs->add($this->getProduct()->name);
        return $this->breadcrumbs;
    }
}
