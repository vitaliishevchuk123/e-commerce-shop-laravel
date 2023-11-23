<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ElasticsearhService;
use Illuminate\Console\Command;

class ElasticAddIndexProduct extends Command
{
    protected $signature = 'app:elastic-add-index-product';

    protected $description = 'Elastic add index product';

    public function handle(ElasticsearhService $elastic)
    {
        $this->info('Start adding Elastic index to all products. This might take a while...');
        foreach (Product::cursor() as $product) {
            $elastic->indexProduct($product);
        }
        $this->info('Done!');
    }
}
