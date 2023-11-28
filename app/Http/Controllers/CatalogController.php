<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\ElasticsearhService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(string $slug, Breadcrumbs $breadcrumbs, Request $request, CategoryRepository $categoryRepository, ElasticsearhService $elasticsearh)
    {
        $category = $categoryRepository->findBySlug($slug);
        if (!$category) {
            abort(404);
        }

        $category->parents(0)->map(function (Category $category) use ($breadcrumbs) {
            $breadcrumbs->add($category->name, $category->getCatalogUrl());
        });

        $request->merge(['category' => $slug]);
        $paginator = $elasticsearh->search($request);

        $filters = Attribute::with('values')->get();

        return Inertia::render('Catalog', [
            'title' => 'Catalog' . $category->name,
            'breadcrumbs' => $breadcrumbs->crumbs(),
            'category' => CategoryResource::make($category),
            'products' => ProductResource::collection($paginator->getCollection()),
            'categorySiblings' => CategoryResource::collection($categoryRepository->getChildrenOrSiblingsAndSelf($category)),
            'filters' => AttributeResource::collection($filters)
        ]);
    }
}
