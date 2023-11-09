<img alt="img" src="./public/img/readme/logo.svg" style="display: block; margin: 0 auto; padding-top: 20px"/>
<img width="200" alt="img" src="./public/img/readme/girl-training.svg" style="display: block; right: 0; top: 0; position: absolute";/>

<h1 align="center" style="color: dodgerblue">E-commerce Shop</h1>

## About project

Ecommerce Shop with Laravel/Inertia/VueJs and API's. 

Will use some features, such as:

- Authentication with Laravel Breeze for site
- Separate admin panel auth /admin/login using Laravel Filament.
- Spatie permissions. Only user with role Admin has access to admin panel. See UserSeeder generate a few test users with role Admin

## Installation
- Install Redis for horizon
- Install DB (mysql/postgres)
- Copy from .env.example and create a new .env file. In it we register a connection to the database
- Run the commands:
```
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
php artisan serve
php artisan horizon
vite
```

## Detail

### Category tree
Use package `efureev/laravel-trees`  for Category tree structure with multi tree.

To enable multi tree structure set true:

```php
class Category extends Model implements TreeConfigurable
{
    use NestedSetTrait;
    //...
    protected static function buildTreeConfig(): Base
    {
        return new Base(true);
    }
    //...
}
``` 

To prevent default relation create behavior in filament use `saveRelationshipsUsing` method
```php
class CategoryResource extends Resource
{
    public static function form(Form $form): Form
        {
            return $form
                ->schema([
                    Forms\Components\Select::make('parent_id')
                        ->relationship(name: 'parent', titleAttribute: 'name')
                        ->getOptionLabelFromRecordUsing(function (Model $record) {
                            return $record->name;
                        })
                        ->saveRelationshipsUsing(function (Category $childNode, $state) {
                            if (!$state) {
                                return;
                            }
                            $parentNode = Category::find($state);
                            $childNode->appendTo($parentNode)->save();
                        })
                        ->searchable()
                        ->preload(),
                        //...
}
```

