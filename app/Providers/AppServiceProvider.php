<?php

namespace App\Providers;

use App\Repositories\CatalogRepository;
use App\Repositories\ElasticCatalogRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindSearchClient();
        $this->app->bind(CatalogRepository::class, ElasticCatalogRepository::class);
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();
        ResourceCollection::withoutWrapping();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts(config('services.elastic.hosts'))
                ->setBasicAuthentication('elastic', ' zcH-vO0emq1OnZaULGRS')
                ->build();
        });
    }
}
