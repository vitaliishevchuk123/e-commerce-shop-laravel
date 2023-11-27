<?php

namespace App\Parsers;

use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class CategoryParser extends AbstractParser
{
    private string $imgDirectory = 'categories';

    public function parse()
    {
        //Save root cat
        $mainSportCat = Category::firstOrCreate(
            [
                'slug' => 'trenazhery',
            ],
            [
                'name' => 'Тренажери Епіцентр',
            ]);
        if (!$mainSportCat->image) {
            $this->saveImg('https://epicentrk.ua/upload/iblock/9d1/Trenazheri.jpg', $mainSportCat);
        }

        $this->saveCats($mainSportCat);

    }

    public function saveCats(Category $rootCategory)
    {
        $url = "https://epicentrk.ua/ua/shop/{$rootCategory->slug}/";
        dump($url);
        $crawler = $this->request('GET', $url);
        $crawler->filter('.shop-category__picture')
            ->each(function (Crawler $node) use ($rootCategory) {
                $slug = str($node->attr('href'))->after('shop/')->trim('/');
                $imgNode = $node->filter('img')->first();
                //Save cat
                $childCat = Category::firstOrCreate(
                    [
                        'slug' => $slug,
                    ],
                    [
                        'name' => $imgNode->attr('alt'),
                    ]);
                if($childCat->wasRecentlyCreated) {
                    $childCat->appendTo($rootCategory);
                }

                //Save cat img
                $imgUrl = $imgNode->attr('src');
                if (!$childCat->image && !empty($imgUrl)) {
                    $this->saveImg($imgUrl, $childCat);
                }
                dump("$childCat->name saved");
                $this->saveCats($childCat);
            });
    }

    public function saveImg(string $url, Category $category): bool
    {
        $response = Http::get($url);
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $filePath = $this->imgDirectory . '/' . $category->slug . '.' . $extension;

        if ($response->successful()) {
            // Зберігання завантаженого зображення у вказану директорію
            Storage::put('public/' . $filePath, $response->body());
            $category->image = $filePath;
            $category->save();
            return true;
        }

        return false;
    }
}
