<?php

namespace App\Http\Controllers;
use App\Helpers\Breadcrumbs;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(string $slug, Breadcrumbs $breadcrumbs)
    {
        $category = Category::findBySlug($slug);
        if (!$category) {
            abort(404);
        }

        $category->parents(0)->map(function (Category $category) use ($breadcrumbs) {
            $breadcrumbs->add($category->name, $category->getCatalogUrl());
        });
        return Inertia::render('Catalog', [
            'title' => 'Catalog' . $category->name,
            'breadcrumbs' => $breadcrumbs->crumbs(),
            'category' => CategoryResource::make($category),
        ]);
    }
}
