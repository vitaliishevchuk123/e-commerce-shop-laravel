<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Category;
use App\Services\ElasticsearhService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(string $slug, Breadcrumbs $breadcrumbs, Request $request, ElasticsearhService $elasticsearh)
    {

        $category = Category::findBySlug($slug);
        if (!$category) {
            abort(404);
        }

        $category->parents(0)->map(function (Category $category) use ($breadcrumbs) {
            $breadcrumbs->add($category->name, $category->getCatalogUrl());
        });

        $categorySiblings = $category->children;
        if ($categorySiblings->isEmpty()) {
            $categorySiblings = $category->siblingsAndSelf()->get();
        }

        $request->merge(['category' => $slug]);
        $paginator = $elasticsearh->search($request);

        $filters = Attribute::with('values')->get();

        return Inertia::render('Catalog', [
            'title' => 'Catalog' . $category->name,
            'breadcrumbs' => $breadcrumbs->crumbs(),
            'category' => CategoryResource::make($category),
            'products' => ProductResource::collection($paginator->getCollection()),
            'categorySiblings' => CategoryResource::collection($categorySiblings),
            'filters' => AttributeResource::collection($filters)
        ]);
    }
}
