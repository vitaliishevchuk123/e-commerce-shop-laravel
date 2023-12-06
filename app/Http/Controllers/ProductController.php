<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __invoke(ProductRepository $productRepository)
    {
        return Inertia::render('Product', [
            'product' => ProductResource::make($productRepository->getProduct()),
            'breadcrumbs' => $productRepository->breadcrumbs()->crumbs()
        ]);
    }
}
