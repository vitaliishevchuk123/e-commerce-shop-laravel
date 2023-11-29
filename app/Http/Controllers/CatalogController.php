<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Repositories\CatalogRepository;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(CatalogRepository $catalogRepository)
    {
        return Inertia::render('Catalog', [
            'title' => 'Catalog' . $catalogRepository->category()->name,
            'breadcrumbs' => $catalogRepository->breadcrumbs()->crumbs(),
            'category' => CategoryResource::make($catalogRepository->category()),
            'categorySiblings' => CategoryResource::collection($catalogRepository->getChildrenOrSiblingsAndSelfCats()),
            'filters' => AttributeResource::collection($catalogRepository->filters()),
            'products' => ProductResource::collection($catalogRepository->categoryProducts()->getCollection()),
            'total' => $catalogRepository->categoryProducts()->total(),
            'currentPage' => $catalogRepository->categoryProducts()->currentPage(),
            'perPage' => $catalogRepository->perPage(),
        ]);
    }

    public function loadMore(CatalogRepository $catalogRepository)
    {
        return ProductResource::collection($catalogRepository->categoryProducts()->getCollection());
    }
}
