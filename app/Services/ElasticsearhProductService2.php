<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Label;
use App\Models\Product;
use Elastic\Elasticsearch\Client;
use Illuminate\Http\Request;

class ElasticsearhProductService2
{
    const INDEX = 'ecom';

    public function __construct(private Client $elasticsearch)
    {
    }

    public function indexProduct(Product $product): string
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
            'index' => self::INDEX,
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

        try {
            $result = $this->elasticsearch->index($document);
            return $result['result'];
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function search(Request $request)
    {
        $requestFilterValues = Attribute::pluck('code')->toArray();

        array_push($requestFilterValues, 'category', 'brand');

        $requestFilters = $request->only($requestFilterValues);

//        $requestFilters[] = 'type';

        $params = [
            'index' => self::INDEX,
            'size' => 25, //Paginate items on Page
            'body' => [
                'aggs' => [
                    'aggs_all_filters' => [
                        'filter' => [
                            'bool' => [
                                'filter' => [
                                    [
                                        'multi_match' => [
                                            'query' => $request->get('search', ''),
                                            'fields' => ['tittle', 'description', 'all_filters'],
                                            'operator' => 'and',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'aggs' => [
                            'facets' => [
                                'nested' => [
                                    'path' => 'filters'
                                ],
                                'aggs' => [
                                    'names' => [
                                        'terms' => [
                                            'field' => 'filters.name'
                                        ],
                                        'aggs' => [
                                            'values' => [
                                                'terms' => [
                                                    'field' => 'filters.value',
                                                    'order' => [
                                                        '_key' => 'asc',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'post_filter' => [],
            ],
        ];

        if ($request->missing('search')) {
            unset($params['body']['aggs']['aggs_all_filters']['filter']['bool']['filter'][0]);
        }

        if ($request->has('search') && !empty($request->get('search'))) {
            $params['body']['query'] = [
                'bool' => [
                    'must' => [
                        [
                            'multi_match' => [
                                'query' => $request->get('search'),
                                'fields' => ['tittle', 'description', 'all_filters'],
                                'operator' => 'and',
                            ],
                        ],
                    ],
                ],
            ];
        }

        if (count($requestFilters)) {
            $requestFilters = array_filter($requestFilters, function ($value) {
                return !empty($value);
            });

            if (count($requestFilters)) {
                $aggsFilters = [];
                foreach ($requestFilters as $key => $values) {
                    if (count($requestFilters) > 1) {
                        $diff = array_values(array_diff(array_keys($requestFilters), [$key]));
                        $aggsFilters[$key] = $diff;
                    }

                    if (count($requestFilters) === 1) {
                        $aggsFilters[$key][] = $key;
                    }
                }

                foreach ($aggsFilters as $key => $innerFacets) {
                    foreach ($innerFacets as $innerKey => $filterKey) {
                        $aggsFilters[$key][$filterKey] = explode(',', $requestFilters[$filterKey]);
                        unset($aggsFilters[$key][$innerKey]);
                    }
                }

                if (count($aggsFilters)) {
                    foreach ($aggsFilters as $outerAggKey => $innerAggs) {
                        foreach ($innerAggs as $key => $values) {

                            $addFilterNameAndValues = [
                                [
                                    'term' => [
                                        'filters.name' => $key,
                                    ]
                                ],
                            ];

                            foreach ($values as $value) {
                                $addFilterNameAndValues[1] = [
                                    'bool' => [
                                        'must' => [
                                            [
                                                'term' => [
                                                    'filters.value' => $value,
                                                ],
                                            ],
                                        ],
                                    ],
                                ];

                            }

                            $params['body']['aggs']['aggs_' . $outerAggKey] = [
                                'filter' => [
                                    'bool' => [
                                        'filter' => [
                                            [
                                                'nested' => [
                                                    'path' => 'filters',
                                                    'query' => [
                                                        'bool' => [
                                                            'filter' => $addFilterNameAndValues,
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'aggs' => [
                                    'facets' => [
                                        'nested' => [
                                            'path' => 'filters',
                                        ],
                                        'aggs' => [
                                            'aggs_special' => [
                                                'filter' => [
                                                    'match' => [
                                                        'filters.name' => $outerAggKey,
                                                    ]
                                                ],
                                                'aggs' => [
                                                    'names' => [
                                                        'terms' => [
                                                            'field' => 'filters.name'
                                                        ],
                                                        'aggs' => [
                                                            'values' => [
                                                                'terms' => [
                                                                    'field' => 'filters.value',
                                                                    'order' => [
                                                                        '_key' => 'asc',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ];
                        }
                    }
                }

                $i = 0;

                foreach ($requestFilters as $filter => $values) {

                    $params['body']['aggs']['aggs_all_filters']['filter']['bool']['filter'][$i] = [
                        'nested' => [
                            'path' => 'filters',
                            'query' => [
                                'bool' => [
                                    'must' => [
                                        [
                                            'term' => [
                                                'filters.name' => $filter,
                                            ],
                                        ],
                                    ],
                                    'should' => [],
                                    'minimum_should_match' => 1,
                                ]
                            ]
                        ]
                    ];

                    $values = explode(',', $values);
                    foreach ($values as $value) {
                        $params['body']['aggs']['aggs_all_filters']['filter']['bool']['filter'][$i]['nested']['query']['bool']['should'][] = [
                            'term' => [
                                'filters.value' => $value,
                            ]
                        ];
                    }

                    $params['body']['post_filter']['bool']['filter'][$i] = [
                        'nested' => [
                            'path' => 'filters',
                            'query' => [
                                'bool' => [
                                    'must' => [
                                        [
                                            'term' => [
                                                'filters.name' => $filter,
                                            ]
                                        ],
                                    ],
                                    'should' => [],
                                    'minimum_should_match' => 1,
                                ]
                            ]
                        ]
                    ];

                    foreach ($values as $value) {
                        $params['body']['post_filter']['bool']['filter'][$i]['nested']['query']['bool']['should'][] = [
                            'term' => [
                                'filters.value' => $value,
                            ]
                        ];
                    }

                    $i++;
                }
            }
        }
//        echo '<pre>';
//        echo json_encode($params);
//        die;
        $res = $this->elasticsearch->search($params);
        dd($res);
    }
}
