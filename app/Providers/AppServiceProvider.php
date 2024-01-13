<?php

namespace App\Providers;

use App\Delivery\CalculateNovaPoshtaDeliveryPrice;
use App\Delivery\CalculateUkrposhtaDeliveryPrice;
use App\Http\Kernel;
use App\Http\Responses\CartResponse;
use App\Http\Responses\CartSuccessResponse;
use App\Repositories\CartProductRepository;
use App\Repositories\CatalogRepository;
use App\Repositories\ElasticCatalogRepository;
use Carbon\CarbonInterval;
use DeliveryModule\DeliveryManager;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use NovaPoshta\NovaPoshta;
use NumberFormatter;
use Sheva\Cart\Contracts\CartsOneClickResponse as CartOneClickResponseContract;
use Sheva\Cart\Contracts\CartsSuccessViewResponse;
use Sheva\Cart\Contracts\CartsViewResponse;
use Sheva\Cart\Helpers\DeliveryPrice;
use Sheva\Cart\Http\Responses\CartOneClickResponse;
use UkrPoshta\UkrPoshta;
use Sheva\PackagesContracts\Contracts\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindSearchClient();
        $this->app->bind(CatalogRepository::class, ElasticCatalogRepository::class);

        $this->app->bind(ProductRepository::class, CartProductRepository::class);

        /** Cart responses register */
        $this->app->singleton(CartsSuccessViewResponse::class, CartSuccessResponse::class);
        $this->app->singleton(CartsViewResponse::class, CartResponse::class);
        $this->app->singleton(CartOneClickResponseContract::class, CartOneClickResponse::class);

        /** Register delivery methods */
//        resolve(DeliveryManager::class)->extend('nova-poshta', function () {
//            return new NovaPoshta();
//        });
//        resolve(DeliveryManager::class)->extend('ukrposhta', function () {
//            return new UkrPoshta();
//        });

        /** Sums calculation for delivery methods registration */
//        $this->app->bind(DeliveryPrice::getDeliveryPriceAbstract('nova-poshta'), function () {
//            return new CalculateNovaPoshtaDeliveryPrice();
//        });
//        $this->app->bind(DeliveryPrice::getDeliveryPriceAbstract('ukrposhta'), function () {
//            return new CalculateUkrposhtaDeliveryPrice();
//        });

        $this->app->singleton('money-formatter', function (): NumberFormatter {
            $formatter = new NumberFormatter('uk_UA', NumberFormatter::DECIMAL);
            $formatter->setSymbol(NumberFormatter::CURRENCY_SYMBOL, '');
            $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
            return $formatter;
        });
    }

    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        JsonResource::withoutWrapping();
        ResourceCollection::withoutWrapping();

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

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts(config('services.elastic.hosts'))
                ->build();
        });
    }
}
