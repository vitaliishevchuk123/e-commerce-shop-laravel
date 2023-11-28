<?php

namespace App\Parsers;

use App\Models\Category;
use App\Models\Product;
use Symfony\Component\DomCrawler\Crawler;

class CatalogProductParser extends AbstractParser
{
    public function parse()
    {
        $mainSportCat = Category::findBySlug('trenazhery');

        if (!$mainSportCat) {
            return;
        }
        foreach (Category::where('id', '>', $mainSportCat->id)->cursor() as $category) {
            $this->saveProducts($category);
        }

    }

    public function saveProducts(Category $category)
    {
        $url = "https://epicentrk.ua/ua/shop/{$category->slug}/";
        dump($url);

        $crawler = $this->request('GET', $url);
        $crawler->filter('.card__info')
            ->each(function (Crawler $node) use ($category) {
                $slug = str($node->filter('.card__name a')->first()->attr('href'))->after('shop/')->trim('.html');
                //Save product
                $product = $category->products()->updateOrCreate(
                    [
                        'slug' => $slug,
                    ],
                    [
                        'name' => $node->filter('.card__name span')->first()->text(),
                        'price' => str($node->filter('.card__price-old span')->first()->text(''))->trim()->replace(['₴', ' '], '')->toInteger() ?: null,
                        'sale_price' => str($node->filter('.card__price-actual span')->first()->text(''))->trim()->replace(['₴', ' '], '')->toInteger(),
                    ]);

                //Save cat img
                $imgUrl = $node->filter('.card__photo img')->first()?->attr('src');
                if ($product->media->isEmpty() && !empty($imgUrl)) {
                    $this->saveImg($imgUrl, $product);
                }
                dump("$product->name saved");
            });
    }

    public function saveImg(string $url, Product $product): bool
    {
        $product->addMediaFromUrl($url)
            ->setName($product->slug)
            ->toMediaCollection();

        return true;
    }
}
