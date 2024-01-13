<?php

return [
    'domain' => null,
    'prefix' => null,
    'fields' => [
        'client' => [
            'last_name' => false,
            'email' => false,
            'surname' => false,
        ]
    ],
    'cart_lifetime_hours' => 24,
    'user_model' => \App\Models\User::class,
    'user_resource' => \App\Http\Resources\UserResource::class,
    'default_img' => url('img/no-img-available.png'),

    'site_currency' => 'UAH',
];
