<?php

namespace App\Services;

use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Label;
use App\Models\Product;
use Elastic\Elasticsearch\Client;

class ElasticsearhService
{
    public function __construct(private Client $elasticsearch)
    {
    }

    public function indexProduct(Product $product)
    {
        $product->load('attributeValues', 'attributeValues.attribute', 'labels');
        $labels = $product->labels->map(function (Label $label) {
            return [
                'label' => $label->code,
                'color' => $label->color,
            ];
        })->toArray();

        $filters = $product->attributeValues->map(function (AttributeValue $value) {
            return [
                'name' => $value->attribute->code,
                'pretty_name' => $value->attribute->name,
                'value' => $value->code,
                'label' => $value->value,
            ];
        })->toArray();

        $filters[] = [
            'name' => 'type',
            'pretty_name' => 'Type',
            'value' => 'product',
            'label' => 'product',
        ];

        $filters[] = [
            'name' => 'Brand',
            'pretty_name' => 'Brand',
            'value' => $product->brand->name,
            'label' => $product->brand->name,
        ];

        $product->categories->map(function (Category $category) use (&$filters) {
            $filters[] = [
                'name' => 'category',
                'pretty_name' => 'Category',
                'value' => $category->slug,
                'label' => $category->name,
            ];
        })->toArray();

        $document = [
            'index' => 'ecom',
            'type' => 'product',
            'id' => $product->id,
            'body' => [
                'title' => $product->name,
                'description' => $product->description,
                'price' => $product->sale_price,
                'filters' => $filters,
                'labels' => $labels,
            ],
        ];

        if (count($filters) === 0) {
            unset($document['body']['filters']);
        }

        if (count($labels) === 0) {
            unset($document['body']['labels']);
        }

        dd($this->elasticsearch->info());
    }
}
