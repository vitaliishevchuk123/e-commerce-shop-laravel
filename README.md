<h1 align="center" style="color: dodgerblue">E-commerce Shop</h1>

<p align="center">
 Ecommerce Shop with Laravel/Inertia/VueJs and API's
</p>

## About project

Will use some features, such as:

- Authentication with Laravel Breeze for site
- Separate admin panel auth /admin/login using Laravel Filament.
- Spatie permissions. Only user with role Admin has access to admin panel. See UserSeeder generate a few test users with role Admin

## Installation

- Copy from .env.example and create a new .env file. In it we register a connection to the database
- Run the commands:
```
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
php artisan serve
vite
```

