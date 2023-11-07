<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        $attributes = [
            [
                'code' => 'color',
                'name' => 'Color',
                'frontend_type' => 'checkbox',
                'is_required' => true,
                'is_filterable' => true,
                'values' => ['Red', 'Blue', 'Green', 'Yellow', 'Black', 'White'],
            ],
            [
                'code' => 'size',
                'name' => 'Size',
                'frontend_type' => 'radio',
                'is_required' => true,
                'is_filterable' => true,
                'values' => ['Small', 'Medium', 'Large', 'XL'],
            ],
            [
                'code' => 'brand',
                'name' => 'Brand',
                'frontend_type' => 'text',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Nike', 'Adidas', 'Puma', 'Under Armour'],
            ],
            [
                'code' => 'material',
                'name' => 'Material',
                'frontend_type' => 'text',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Polyester', 'Cotton', 'Spandex', 'Leather'],
            ],
            [
                'code' => 'product_type',
                'name' => 'Product Type',
                'frontend_type' => 'checkbox',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Running Shoes', 'Basketball Jerseys', 'Fitness Equipment', 'Tennis Rackets'],
            ],
            [
                'code' => 'gender',
                'name' => 'Gender',
                'frontend_type' => 'radio',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Men', 'Women', 'Unisex'],
            ],
            [
                'code' => 'season',
                'name' => 'Season',
                'frontend_type' => 'checkbox',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Summer', 'Winter', 'Spring', 'Fall'],
            ],
            [
                'code' => 'sport_type',
                'name' => 'Sport Type',
                'frontend_type' => 'checkbox',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Soccer', 'Basketball', 'Yoga', 'Tennis'],
            ],
            [
                'code' => 'feature',
                'name' => 'Feature',
                'frontend_type' => 'checkbox',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Moisture-Wicking', 'Breathable', 'UV Protection', 'Waterproof'],
            ],
            [
                'code' => 'activity',
                'name' => 'Activity',
                'frontend_type' => 'checkbox',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Running', 'Cycling', 'Swimming', 'Hiking'],
            ],
            [
                'code' => 'country_of_origin',
                'name' => 'Country of Origin',
                'frontend_type' => 'text',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['USA', 'China', 'Germany', 'Italy'],
            ],
            [
                'code' => 'age_group',
                'name' => 'Age Group',
                'frontend_type' => 'radio',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Adult', 'Youth', 'Kids'],
            ],
            [
                'code' => 'weight',
                'name' => 'Weight',
                'frontend_type' => 'text',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Lightweight', 'Medium Weight', 'Heavy Weight'],
            ],
            [
                'code' => 'technology',
                'name' => 'Technology',
                'frontend_type' => 'checkbox',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Air Cushion', 'Quick Dry', 'Gore-Tex', 'EVA Foam'],
            ],
            [
                'code' => 'closure_type',
                'name' => 'Closure Type',
                'frontend_type' => 'radio',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Lace-Up', 'Velcro', 'Zipper'],
            ],
            [
                'code' => 'athlete_endorsement',
                'name' => 'Athlete Endorsement',
                'frontend_type' => 'checkbox',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['LeBron James', 'Serena Williams', 'Cristiano Ronaldo', 'Kobe Bryant'],
            ],
            [
                'code' => 'fit_type',
                'name' => 'Fit Type',
                'frontend_type' => 'radio',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['Regular Fit', 'Slim Fit', 'Loose Fit'],
            ],
            [
                'code' => 'warranty',
                'name' => 'Warranty',
                'frontend_type' => 'text',
                'is_required' => false,
                'is_filterable' => true,
                'values' => ['1 Year', '2 Years', 'Lifetime'],
            ],
        ];

        foreach ($attributes as $attributeData) {
            $values = $attributeData['values'];
            unset($attributeData['values']);

            $attribute = Attribute::query()->create($attributeData);

            foreach ($values as $value) {
                $attribute->values()->create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
    }
}

