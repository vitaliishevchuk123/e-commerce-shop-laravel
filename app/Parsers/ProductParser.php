<?php

namespace App\Parsers;

use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class ProductParser extends AbstractParser
{
    private string $imgDirectory = 'products';

    public function parse()
    {
        $mainSportCat = Category::findBySlug('trenazhery');

        if (!$mainSportCat) {
            return;
        }
        foreach(Category::where('id', '>', $mainSportCat->id)->cursor() as $category) {
            $this->saveProducts($category);
        }

    }

    public function saveProducts(Category $category)
    {
        $url = "https://epicentrk.ua/ua/shop/{$category->slug}/";
        dump($url);
        $crawler = $this->request('GET', $url);
        $crawler->filter('.product-list-wrapper > .card__info')
            ->each(function (Crawler $node) use ($category) {
                $nodeImgLink = $node->filter('.card__photo')->first();
                $slug = str($nodeImgLink->attr('href'))->after('shop/')->trim('.html');
                dd($slug);

                //Save cat
                $childCat = Category::firstOrCreate(
                    [
                        'slug' => $slug,
                    ],
                    [
                        'name' => $imgNode->attr('alt'),
                    ]);
                if($childCat->wasRecentlyCreated) {
                    $childCat->appendTo($category);
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
