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

    public function putAttributeMapping(): void
    {
        $properties = Attribute::get()->mapWithKeys(function (Attribute $attribute) {
            return [
                $attribute->code => [
                    'type' => 'keyword'
                ]
            ];
        })->toArray();

        $mappingParams = [
            'index' => 'products',
            'body' => [
                'properties' => $properties,
            ],
        ];

        $this->elasticsearch->indices()->putMapping($mappingParams);

        $params = ['index' => 'products'];

        $this->elasticsearch->indices()->refresh($params);

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
            ->mapWithKeys(function (AttributeValue $value) {
                return [
                    $value->attribute->code => $value->code
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

        $body = array_merge($body, $attributeValues);

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

    public function search(Request $request): LengthAwarePaginator
    {
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $search = $request->input('search');

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

        if ($search) {
            $params['body']['query']['bool']['must'][] = [
                'multi_match' => [
                    'query' => $search,
                    'fields' => ['name', 'description'],
                    'type' => 'most_fields', // Ви можете вибрати інший тип пошуку
                ],
            ];
        }

        foreach ($request->except('page', 'search') as $field => $values) {
            if (empty($values)) {
                continue;
            }
            $values = array_filter(explode(',', $values));
            if (!count($values)) {
                continue;
            }

            if (count($values) === 1) {
                $params['body']['query']['bool']['must'][] = [
                    'match' => [
                        $field => $values[0],
                    ],
                ];
                continue;
            }

            $values = array_map(function ($v) use ($field) {
                return [
                    'match' => [$field => $v]
                ];
            }, $values);

            $params['body']['query']['bool'] = [
                'should' => $values,
                'minimum_should_match' => 1
            ];
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
}
