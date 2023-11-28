<?php

namespace App\Parsers;
use Goutte;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;

class AbstractParser
{
    protected bool $waitJs = false;
    protected ?Client $client = null;

    public function __construct()
    {
        if ($this->waitJs) {
            $this->client = Client::createChromeClient();
        }
    }

    protected function request(string $method, string $url): Crawler
    {
        if (!$this->waitJs) {
            return Goutte::request($method, $url);
        }

        $this->client->request($method, $url);
        return $this->client->getCrawler();
    }
}
