<?php

namespace App\Repositories;

use App\Helpers\Breadcrumbs;
use App\Models\Attribute;
use App\Models\Category;
use App\Services\ElasticsearhProductService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ElasticCatalogRepository implements CatalogRepository
{
    private ?Category $category;

    public function __construct(
        private Request             $request,
        private ElasticsearhProductService $elasticsearh,
        private CategoryRepository  $categoryRepository,
        private Breadcrumbs         $breadcrumbs)
    {
        $this->category = $categoryRepository->findBySlug($request->category);
        if (!$this->category) {
            abort(404);
        }
    }

    public function getChildrenOrSiblingsAndSelfCats(): Collection
    {
       return  $this->categoryRepository->getChildrenOrSiblingsAndSelf($this->category);
    }


    public function categoryProducts(): LengthAwarePaginator
    {
        $this->request->merge(['category' => $this->category->slug]);
        return $this->elasticsearh->search($this->request);
    }

    public function filters(): Collection
    {
        return Attribute::with('values')->get();
    }

    public function breadcrumbs(): Breadcrumbs
    {
        if ($this->breadcrumbs->crumbs()->isEmpty()) {
            $this->category->parents(0)->map(function (Category $category) {
                $this->breadcrumbs->add($category->name, $category->getCatalogUrl());
            });
        }

        return $this->breadcrumbs;
    }

    public function category(): Category
    {
        return $this->category;
    }
}
