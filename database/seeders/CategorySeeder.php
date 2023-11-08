<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => [
                    'en' => 'Fitness',
                    'uk' => 'Фітнес',
                    'ru' => 'Фитнес',
                ],
                'children' => [
                    [
                        'name' => [
                            'en' => 'Cardio Equipment',
                            'uk' => 'Кардіо обладнання',
                            'ru' => 'Кардио оборудование',
                        ],
                        'children' => [
                            ['name' => ['en' => 'Treadmills', 'uk' => 'Бігові доріжки', 'ru' => 'Беговые дорожки']],
                            ['name' => ['en' => 'Exercise Bikes', 'uk' => 'Велотренажери', 'ru' => 'Велотренажеры']],
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Strength Training',
                            'uk' => 'Силовий тренування',
                            'ru' => 'Силовые тренировки',
                        ],
                        'children' => [
                            ['name' => ['en' => 'Dumbbells', 'uk' => 'Гантелі', 'ru' => 'Гантели']],
                            ['name' => ['en' => 'Barbells', 'uk' => 'Штанги', 'ru' => 'Штанги']],
                        ],
                    ],
                ],
            ],
            [
                'name' => [
                    'en' => 'Team Sports',
                    'uk' => 'Командні види спорту',
                    'ru' => 'Командные виды спорта',
                ],
                'children' => [
                    [
                        'name' => [
                            'en' => 'Soccer',
                            'uk' => 'Футбол',
                            'ru' => 'Футбол',
                        ],
                        'children' => [
                            ['name' => ['en' => 'Soccer Balls', 'uk' => 'Футбольні м\'ячі', 'ru' => 'Футбольные мячи']],
                            ['name' => ['en' => 'Goal Posts', 'uk' => 'Ворота', 'ru' => 'Ворота']],
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Basketball',
                            'uk' => 'Баскетбол',
                            'ru' => 'Баскетбол',
                        ],
                        'children' => [
                            ['name' => ['en' => 'Basketballs', 'uk' => 'Баскетбольні м\'ячі', 'ru' => 'Баскетбольные мячи']],
                            ['name' => ['en' => 'Hoops', 'uk' => 'Кільця', 'ru' => 'Кольца']],
                        ],
                    ],
                ],
            ],
            [
                'name' => [
                    'en' => 'Outdoor Activities',
                    'uk' => 'Активний відпочинок на природі',
                    'ru' => 'Активный отдых на природе',
                ],
                'children' => [
                    [
                        'name' => [
                            'en' => 'Camping & Hiking',
                            'uk' => 'Кемпінг і похід',
                            'ru' => 'Кемпинг и поход',
                        ],
                        'children' => [
                            ['name' => ['en' => 'Tents', 'uk' => 'Намети', 'ru' => 'Палатки']],
                            ['name' => ['en' => 'Backpacks', 'uk' => 'Рюкзаки', 'ru' => 'Рюкзаки']],
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Water Sports',
                            'uk' => 'Водні види спорту',
                            'ru' => 'Водные виды спорта',
                        ],
                        'children' => [
                            ['name' => ['en' => 'Kayaking', 'uk' => 'Каякинг', 'ru' => 'Каякинг']],
                            ['name' => ['en' => 'Surfing', 'uk' => 'Серфінг', 'ru' => 'Серфинг']],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
