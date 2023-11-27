<?php

namespace App\Parsers;
use Goutte;
use Symfony\Component\DomCrawler\Crawler;

class AbstractParser
{
    protected function request(string $method, string $url): Crawler
    {
        return Goutte::request($method, $url);
    }
}
