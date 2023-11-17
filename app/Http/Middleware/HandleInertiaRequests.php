<?php

namespace App\Http\Middleware;

use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $catRepository = app(CategoryRepository::class);
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'message' => $request->session()->get('message'),
                'errors' => $request->session()->get('errors'),
            ],
            'ziggy' => fn() => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'catalogRootCats' => CategoryResource::collection($catRepository->catalogRootCats()),
            'locale' => function () {
                return app()->getLocale();
            },
            'language' => function () {
                $filePath = base_path('/lang/' . app()->getLocale() . '.json');
                if (!File::exists($filePath)) {
                    return [];
                }
                return json_decode(
                    File::get($filePath),
                    true
                );
            },
        ];
    }
}
