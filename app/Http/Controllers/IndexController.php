<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Models\Brand;
use App\Repositories\CategoryRepository;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function index(CategoryRepository $catRepository)
    {
        $brands = Brand::query()->orderBy('order')->take(15)->get();

        return Inertia::render('Home', [
            'title' => 'Home',
            'brands' => BrandResource::collection($brands),
            'homeGymCats' => CategoryResource::collection($catRepository->homeGymCats(10)),
            'fitnessClubGymCats' => CategoryResource::collection($catRepository->fitnessClubGymCats(7)),
        ]);
    }
}
