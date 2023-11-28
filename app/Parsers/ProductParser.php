<?php

namespace App\Parsers;

use App\Models\Attribute;
use App\Models\Product;
use Symfony\Component\DomCrawler\Crawler;

class ProductParser extends AbstractParser
{
    public function parse()
    {
        foreach (Product::where('id', '>', 12)->cursor() as $product) {
            $this->saveProductDetails($product);
        }

    }

    public function saveProductDetails(Product $product)
    {
        $url = "https://epicentrk.ua/ua/shop/{$product->slug}.html";
        dump($url);

        $crawler = $this->request('GET', $url);
        $this->saveAttributes($crawler, $product);
        $this->saveImages($crawler, $product);
        dd($crawler->filter('.p-header__title')->text(''));
    }

    public function saveAttributes(Crawler $crawler, Product $product)
    {
        $crawler->filter('.p-block__row--char-all')
            ->first()
            ->filter('.p-char__item')->each(function (Crawler $node) use ($product) {
                $attName = str($node->filter('.p-char__name-value')->text(''))
                    ->before(':')->trim();
                $attValueName = str($node->filter('.p-char__value span')->text(''))
                    ->before(':')->trim();
                if ($attValueName->isEmpty() || $attName->isEmpty()) {
                    return;
                }

                $attribute = Attribute::where('name->uk', $attName)->first();
                if (!$attribute) {
                    $attribute = Attribute::create([
                        'name' => $attName,
                    ]);
                }

                $value = $attribute->values()->where('value->uk', $attValueName)->first();
                if (!$value) {
                    $value = $attribute->values()->create([
                        'value' => $attValueName,
                    ]);
                }

                if ($value->wasRecentlyCreated) {
                    $product->attributeValues()->attach($value);
                }
            });
    }

    public function saveImages(Crawler $crawler, Product $product): void
    {
        dd($crawler->filter('.swiper-wrapper')->count());
        $crawler->filter('img')->each(function (Crawler $node) use ($product) {
            dump($node->filter('img')->first()->attr('src'));
//                    $product->addMediaFromUrl($node->attr('src'))
//                        ->toMediaCollection();
        });
    }
}
