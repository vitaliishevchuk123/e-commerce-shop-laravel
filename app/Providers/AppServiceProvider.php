<?php

namespace App\Providers;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as HttpClient;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindSearchClient();
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.elastic.hosts'))
                ->setBasicAuthentication('elastic', ' zcH-vO0emq1OnZaULGRS')
                ->build();
        });
    }
}
