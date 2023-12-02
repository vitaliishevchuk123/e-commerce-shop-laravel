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
                        'string_facets' => [
                            'type' => 'nested',
                            'properties' => [
                                'name' => ['type' => 'keyword'],
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
                    'name' => $value->attribute->code,
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

        $body['string_facets'] = $attributeValues;

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
                 * У цьому випадку, string_facets - це вкладене поле, яке містить ключ-значення атрибутів.
                 * Ми створюємо умову, де:
                 * string_facets.name = $field (назвою атрибута) і
                 * string_facets.value = $value (значенням атрибута).
                 */
                $attributeConditions[] = [
                    'nested' => [
                        'path' => 'string_facets',
                        'query' => [
                            'bool' => [
                                'must' => [
                                    ['match' => ['string_facets.name' => $field]],
                                    ['match' => ['string_facets.value' => $value]],
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
        // Запит на агрегацію
        $params['body']['aggs'] = [
            'aggs_text_facets' => [
                'nested' => [
                    'path' => 'string_facets',
                ],
                'aggs' => [
                    'name' => [
                        'terms' => [
                            'field' => 'string_facets.name',
                            'size' => 1000
                        ],
                        'aggs' => [
                            'value' => [
                                'terms' => [
                                    'field' => 'string_facets.value',
                                    'size' => 1000
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];


        $res = $this->elasticsearch->search($params);

        $this->processFacetsResult(collect(Arr::get($res, 'aggregations.aggs_text_facets.name.buckets')));

        $this->processNeighborFacets($request);

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

    public function processNeighborFacets(Request $request): void
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $startParams = [
            'index' => self::INDEX,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [],
                    ],
                ],
                'size' => 0,
            ],
        ];

        //search
        if ($search) {
            $startParams['body']['query']['bool']['must'][] = [
                'multi_match' => [
                    'query' => $search,
                    'fields' => ['name', 'description'],
                    'type' => 'most_fields', // Ви можете вибрати інший тип пошуку
                ],
            ];
        }

        //category
        if ($category) {
            $startParams['body']['query']['bool']['must'][] = [
                'match' => [
                    'category' => $category,
                ],
            ];
        }

        $requestFilterAllValues = Attribute::whereIn('code', array_keys($request->except('page', 'search', 'category')))
            ->with('values')
            ->get();
        $requestSelectedFilters = collect($request->except('page', 'search', 'category'))
            ->filter(fn($values) => !empty($values))
            ->mapWithKeys(function ($values, $k) {
                $values = array_filter(explode(',', $values));
                return [$k => $values];
            })
            ->filter(fn($values) => !empty($values))->toArray();
        if (!count($requestSelectedFilters)) {
            return;
        }
        $neighborFilterValues = $requestFilterAllValues->mapWithKeys(function (Attribute $attribute) use ($requestSelectedFilters) {
            return [
                $attribute->code => $attribute->values
                    ->pluck('code')
                    ->filter(fn($v) => !in_array($v, $requestSelectedFilters[$attribute->code]))
                    ->values()
                    ->toArray()
            ];
        })->toArray();

        $neighborParams = [];
        foreach ($neighborFilterValues as $outerFilter => $outerFilterNeighborValues) {
            if (!count($outerFilterNeighborValues)) {
                continue;
            }
            $neighborParams[$outerFilter] = $startParams;
            foreach ($requestSelectedFilters as $filter => $values) {
                /**
                 * Якщо фільтр поточний вибраний, то замість нього підставляємо сусідів
                 */
                if ($filter === $outerFilter) {
                    $values = $outerFilterNeighborValues;
                }
                $attributeConditions = [];
                foreach ($values as $value) {
                    $attributeConditions[] = [
                        'nested' => [
                            'path' => 'string_facets',
                            'query' => [
                                'bool' => [
                                    'must' => [
                                        ['match' => ['string_facets.name' => $filter]],
                                        ['match' => ['string_facets.value' => $value]],
                                    ],
                                ],
                            ],
                        ],
                    ];


                }
                $neighborParams[$outerFilter]['body']['query']['bool']['must'][] = ['bool' => ['should' => $attributeConditions]];
            }
            $neighborParams[$outerFilter]['body']['aggs'] = [
                'aggs_text_facets' => [
                    'nested' => [
                        'path' => 'string_facets',
                    ],
                    'aggs' => [
                        'name' => [
                            'terms' => [
                                'field' => 'string_facets.name',
                                'size' => 1000
                            ],
                            'aggs' => [
                                'value' => [
                                    'terms' => [
                                        'field' => 'string_facets.value',
                                        'size' => 1000
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        }
        foreach ($neighborParams as $filer => $filterParams) {
            $res = $this->elasticsearch->search($filterParams);
            $resFacets = collect(Arr::get(collect(Arr::get($res, 'aggregations.aggs_text_facets.name.buckets'))
                ->where('key', $filer)->first(), 'value.buckets'))
                ->map(function ($v) use ($requestFilterAllValues, $filer) {
                    return [
                        'value' => $requestFilterAllValues
                            ->where('code', $filer)
                            ->first()->values->where('code', $v['key'])->first(),
                        'facet_count' => $v['doc_count'],
                    ];
                });
            $this->filters->where('attribute.code', $filer)->first()['values']->push(...$resFacets);
        }
    }

    public function processFacetsResult(Collection $facets): void
    {
        $this->filters = Attribute::query()
            ->whereIn('code', $facets->pluck('key')->toArray())
            ->with(['values' => function ($q) use ($facets) {
                $q->whereIn('code', $facets->pluck('value.buckets')->collapse()->pluck('key')->toArray());
            }])
            ->get()
            ->map(function (Attribute $attribute) use ($facets) {
                return [
                    'attribute' => $attribute,
                    'facet_count' => $facets->where('key', $attribute->code)->first()['doc_count'],
                    'values' => $attribute->values->map(function (AttributeValue $value) use ($facets, $attribute) {
                        return [
                            'value' => $value,
                            'facet_count' => collect($facets->where('key', $attribute->code)->first()['value']['buckets'])
                                ->where('key', $value->code)->first()['doc_count'],
                        ];
                    }),
                ];
            });
    }

    public function getFilters(): Collection
    {
        return $this->filters;
    }
}
