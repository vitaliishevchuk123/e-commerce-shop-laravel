<?php

namespace App\Console\Commands;

use App\Parsers\CategoryParser;
use App\Parsers\ProductParser;
use Illuminate\Console\Command;

class Parser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        app(CategoryParser::class)->parse();
        app(ProductParser::class)->parse();
    }
}
