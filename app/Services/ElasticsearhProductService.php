<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use Elastic\Elasticsearch\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class ElasticsearhProductService
{
    const INDEX = 'products';

    public function __construct(private Client $elasticsearch)
    {
    }

    public function deleteIndex()
    {
        $params = [
            'index' => self::INDEX,
        ];

        $this->elasticsearch->indices()->delete($params);
    }

    public function createIndexWithMapping(): void
    {
        $params = [
            'index' => self::INDEX,
            'body' => [
                'mappings' => [
                    'properties' => [
                        'category' => [
                            'type' => 'keyword',
                        ],
                        'label' => [
                            'type' => 'keyword',
                        ],
                        'attributeValues' => [
                            'type' => 'nested',
                            'properties' => [
                                'key' => ['type' => 'keyword'],
                                'value' => ['type' => 'keyword'],
                            ],
                        ],
                    ]
                ]
            ]
        ];

        $this->elasticsearch->indices()->create($params);
    }

    public function indexProduct(Product $product): string
    {
        $product->load('attributeValues', 'attributeValues.attribute', 'labels', 'categories', 'brand');

        $attributeValues = $product->attributeValues
            ->map(function (AttributeValue $value) {
                return [
                    'key' => $value->attribute->code,
                    'value' => $value->code,
                ];
            })
            ->toArray();
        $catSlugs = collect();
        $product->categories
            ->map(function (Category $category) use ($catSlugs) {
                $catSlugs->push(...$category->parents(0)->pluck('slug'));
                $catSlugs->push($category->slug);
            });
        $body = [
            'name' => array_values($product->getTranslations('name')),
            'description' => array_values($product->getTranslations('description')),
            'label' => $product->labels->pluck('code')->toArray(),
            'category' => $catSlugs->unique()->toArray(),
        ];

        if ($product->brand) {
            $body['brand'] = $product->brand->slug;
        }

//        $body = array_merge($body, $attributeValues);
        $body['attributeValues'] = $attributeValues;

        $document = [
            'index' => self::INDEX,
            'id' => $product->id,
            'body' => $body,
        ];

        try {
            $result = $this->elasticsearch->index($document);
            return $result['result'];
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function search(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $currentPage = $request->input('page', 1);
        $search = $request->input('search');
        $category = $request->input('category');
//        dd($this->getAttributesWithFacets($request));

        $params = [
            'index' => self::INDEX,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [],
                    ],
                ],
                'size' => $perPage,
                'from' => ($currentPage - 1) * $perPage,

            ],
        ];

        //search
        if ($search) {
            $params['body']['query']['bool']['must'][] = [
                'multi_match' => [
                    'query' => $search,
                    'fields' => ['name', 'description'],
                    'type' => 'most_fields', // Ви можете вибрати інший тип пошуку
                ],
            ];
        }

        //category
        if ($category) {
            $params['body']['query']['bool']['must'][] = [
                'match' => [
                    'category' => $category,
                ],
            ];
        }

        //attributes
        foreach ($request->except('page', 'search', 'category') as $field => $values) {
            if (empty($values)) {
                continue;
            }
            $values = array_filter(explode(',', $values));
            if (!count($values)) {
                continue;
            }

            // Створення умов пошуку для кожного значення поля
            $attributeConditions = [];

            foreach ($values as $value) {
                $attributeConditions[] = [
                    'nested' => [
                        'path' => 'attributeValues',
                        'query' => [
                            'bool' => [
                                'must' => [
                                    ['match' => ['attributeValues.key' => $field]],
                                    ['match' => ['attributeValues.value' => $value]],
                                ],
                            ],
                        ],
                    ],
                ];
            }

            $params['body']['query']['bool']['must'][] = ['bool' => ['should' => $attributeConditions]];
        }


        $res = $this->elasticsearch->search($params);

        $total = Arr::get($res, 'hits.total.value');
        $products = collect();

        if ($total > 0) {
            $products = Product::query()
                ->with(['media', 'attributeValues', 'attributeValues.attribute'])
                ->whereIn('id', Arr::pluck(Arr::get($res, 'hits.hits'), '_id'))
                ->get();
        }

//        dd($this->searchWithFacets($request));

        return new LengthAwarePaginator(
            $products,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query->all(),
                "pageName" => "page"
            ]
        );
    }

    public function searchWithFacets(Request $request): array
    {
        $params = [
            'index' => self::INDEX,
            'body' => [
                'size' => 0, // Ми не хочемо отримувати результати, лише агрегації
                'aggs' => [],
            ],
        ];

        foreach ($request->except('page', 'search') as $field => $values) {
            if (empty($values)) {
                continue;
            }
            $values = array_filter(explode(',', $values));
            if (!count($values)) {
                continue;
            }

            $aggregation = [
                'terms' => [
                    'field' => $field,
                ],
            ];

            $params['body']['aggs'][$field] = $aggregation;
        }

        $res = $this->elasticsearch->search($params);

        $facets = [];

        foreach ($request->except('page', 'search') as $field => $values) {
            if (empty($values)) {
                continue;
            }
            $values = array_filter(explode(',', $values));
            if (!count($values)) {
                continue;
            }

            $buckets = Arr::get($res, "aggregations.$field.buckets");
            $facets[$field] = $buckets;
        }

        return $facets;
    }

    private function getAttributesWithFacets(Request $request): array
    {
        $categorySlug = $request->category;

        $attributeAggregationParams = [
            'index' => self::INDEX,
            'body' => [
                'size' => 0,
                'aggs' => [
                    'attributes' => [
                        'nested' => [
                            'path' => 'attributeValues' // Перевірте цей шлях, він може змінюватися в залежності від вашої моделі
                        ],
                        'aggs' => [
                            'unique_attributes' => [
                                'terms' => [
                                    'field' => 'attributeValues.attribute.code.keyword',
                                    'size' => 10 // Розмір фасетів атрибутів
                                ]
                            ]
                        ]
                    ]
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => [
                                    'category' => $categorySlug
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $attributeAggregations = $this->elasticsearch->search($attributeAggregationParams);

        $attributes = collect(Arr::get($attributeAggregations, 'aggregations.attributes.unique_attributes.buckets'))
            ->pluck('key')
            ->map(function ($attribute) use ($categorySlug) {
                $facetParams = [
                    'index' => self::INDEX,
                    'body' => [
                        'size' => 0,
                        'aggs' => [
                            'values_' . $attribute => [
                                'terms' => [
                                    'field' => $attribute . '.keyword',
                                    'size' => 10 // Розмір фасетів
                                ]
                            ]
                        ],
                        'query' => [
                            'bool' => [
                                'must' => [
                                    [
                                        'term' => [
                                            'category' => $categorySlug
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];

                $facetResults = $this->elasticsearch->search($facetParams);

                $facets = Arr::get($facetResults, 'aggregations.values_' . $attribute . '.buckets');
                $facetValues = collect($facets)->pluck('key')->toArray();

                return [
                    'attribute_code' => $attribute,
                    'facet_values' => $facetValues,
                ];
            })
            ->toArray();

        return $attributes;
    }
}
