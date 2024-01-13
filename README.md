<img alt="img" src="./public/img/readme/logo.svg" style="display: block; margin: 0 auto; padding-top: 20px"/>
<img width="200" alt="img" src="./public/img/readme/girl-training.svg" style="display: block; right: 0; top: 0; position: absolute";/>

<h1 align="center" style="color: dodgerblue">E-commerce Shop</h1>

## About project

Ecommerce Shop with Laravel/Inertia/VueJs 3/Filament and API's. 

Will use some features, such as:

- Authentication with Laravel Breeze for site
- Separate admin panel auth /admin/login using Laravel Filament.
- Spatie permissions. Only user with role Admin has access to admin panel. See UserSeeder generate a few test users with role Admin

## Installation
- Install Redis for horizon
- Install DB (mysql/postgres)
- Copy from .env.example and create a new .env file. In it we register a connection to the database
- Run the commands:
### Backend
```
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan db:seed
npm install
php artisan serve
php artisan horizon
vite
```
### Frontend
By default, you don't need npm install. Because project has built front files
```
npm install
npm run build
//or
npm run dev
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
                        ->saveRelationshipsUsing(function ([.bashrc](..%2F..%2F.bashrc)Category $childNode, $state) {
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

### Stubs

Modify migration stubs:

```php
public function down(): void
    {
        if (app()->isLocal()) {
            //
        }
    }
``` 

### Strict mode

Enable strict mode for local environment:

```php
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
        {
            Model::shouldBeStrict(!app()->isProduction());
``` 

### Telegram logger

Add telegram logger:

```php
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    //...
        if (app()->isProduction()) {
            $this->logLongRequests();
        }
    }
    
    public function logLongRequests()
    {
        DB::listen(function ($query) {
            if ($query->time > 100) {
                logger()
                    ->channel('telegram')
                    ->debug("🛠 Need fix SQL 👨🏾‍🔧🔧 \n Query longer then 1ms:  . $query->sql, $query->bindings");
            }
        });

        app(Kernel::class)->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(4),
            function () {
                logger()->channel('telegram')
                    ->debug("⚙️ Need fix Request 👨🏾‍🔧🔧 \n Long term query: " . request()->url());
            }
        );
    }
}

// config/logging.php
'telegram' => [
    'driver' => 'custom',
    'via' => TelegramLoggerFactory::class,
    'level' => env('LOG_LEVEL', 'debug'),
    'chat_id' => env('LOGGER_TELEGRAM_CHAT_ID', ''),
    'token' => env('LOGGER_TELEGRAM_BOT_TOKEN', ''),
],
``` 

## Dev tips

### Permissions
Without going into bash, just in the project folder for the first time give the perms
```
sudo chmod -R 777  ./
```

## Docker
```
docker-compose build --no-cache
docker-compose up --build --force-recreate --no-deps
docker-compose down
docker-compose up -d
docker exec -it project_name_app bash
systemctl restart docker
```

### Aliases Mac OS
For those new to creating bash aliases, the process is pretty simple. 
First, open up the ~/.zshrc file in a text editor that is found in your home directory.
Uncomment or add the following lines:
```
cd ~ 
open .zshrc
```
Paste text alias `.zshrc` and save file:
```
alias a="php artisan"
```
Reopen terminal
