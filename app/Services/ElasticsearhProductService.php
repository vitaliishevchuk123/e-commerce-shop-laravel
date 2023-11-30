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
use Illuminate\Support\Collection;

class ElasticsearhProductService
{
    const INDEX = 'products';

    private Collection $filters;

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
        /**
         * 'type' => 'keyword'
         * поля, що містять ключові слова або фіксований список значень.
         * шукатиме по точному співпадінню
         */
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
//        dump($this->getAttributesWithFacets($request));

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

            $attributeConditions = [];

            foreach ($values as $value) {
                /**
                 * Кожна умова для пошуку атрибута конкретного продукта знаходиться всередині nested запиту Elasticsearch.
                 * Це означає, що ми шукаємо вкладене поле (nested field) в документах.
                 * У цьому випадку, attributeValues - це вкладене поле, яке містить ключ-значення атрибутів.
                 * Ми створюємо умову, де:
                 * attributeValues.key = $field (назвою атрибута) і
                 * attributeValues.value = $value (значенням атрибута).
                 */
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

            /**
             * Кожна умова для пошуку, яку ми створили в попередньому кроці, додається до $params['body']['query']['bool']['must'].
             * Elasticsearch очікує параметри запиту в певному форматі, де 'bool' - це булева логіка, яка вказує, що всі умови мають виконатися.
             * В даному випадку, 'should' вказує, що хоча б одна з умов повинна виконатися, щоб запит повернув результат.
             */
            $params['body']['query']['bool']['must'][] = ['bool' => ['should' => $attributeConditions]];
        }

        //facets
        $params['body']['aggs'] = [
            'attributes' => [
                'nested' => [
                    'path' => 'attributeValues'
                ],
                'aggs' => [
                    'unique_attributes' => [
                        'terms' => [
                            'field' => 'attributeValues.value',
                            'size' => 1000
                        ]
                    ]
                ]
            ]
        ];


        $res = $this->elasticsearch->search($params);

        $this->processFacetsResult(collect(Arr::get($res, 'aggregations.attributes.unique_attributes.buckets')));

        $total = Arr::get($res, 'hits.total.value');
        $products = collect();

        if ($total > 0) {
            $products = Product::query()
                ->with(['media', 'attributeValues', 'attributeValues.attribute'])
                ->whereIn('id', Arr::pluck(Arr::get($res, 'hits.hits'), '_id'))
                ->get();
        }

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

    public function processFacetsResult(Collection $facets): void
    {
        $attributes = AttributeValue::query()
            ->with('attribute')
            ->whereIn('code', $facets->pluck('key')->toArray())
            ->get()
            ->groupBy('attribute.id')
            ->map(function (Collection $values) use ($facets) {
                /** @var Attribute $attr */
                $attr = $values->first()->attribute;
                $attr->setRelation('values', $values->map(function (AttributeValue $v) use($facets) {
                    $v->unsetRelation('attribute');
                    return [
                        'value' => $v,
                        'facet_count' => $facets->where('key', $v->code)->first()['doc_count'],
                    ];
                }));
                return $attr;
            })->values();
        $this->filters = $attributes;
    }

    public function getFilters(): Collection
    {
        return $this->filters;
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
                            'path' => 'attributeValues'
                        ],
                        'aggs' => [
                            'unique_attributes' => [
                                'terms' => [
                                    'field' => 'attributeValues.value',
                                    'size' => 1000
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
        dd($attributeAggregations['aggregations']);

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
