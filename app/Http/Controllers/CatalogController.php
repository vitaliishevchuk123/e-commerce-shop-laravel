<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(string $slug)
    {
        $category = Category::findBySlug($slug);
        if (!$category) {
            abort(404);
        }
        return Inertia::render('Catalog', [
            'title' => 'Catalog',
        ]);
    }
}
