<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function index()
    {
        $brands = Brand::query()->orderBy('order')->take(15)->get();
        return Inertia::render('Home', [
            'title' => 'Home',
            'brands' => BrandResource::collection($brands),

        ]);
    }
}
