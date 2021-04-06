<?php
/**
 * Copyright (c) 2021 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.digital, jz@strategio.digital)
 */
declare(strict_types=1);

namespace App;

use Symfony\Component\DomCrawler\Crawler;

final class Scraper
{
    protected ElasticManager $elasticManager;

    protected Crawler $crawler;

    /**
     * Create our services - better way is DI container :-)
     */
    public function __construct()
    {
        $this->elasticManager = new ElasticManager;
        $this->crawler = new Crawler;
    }

    public function run() : void
    {
        // Load html content from external URL
        $html = file_get_contents('https://foxentry.cz/funkce/');
        $this->crawler->addHtmlContent($html);

        // Extract data from raw-html
        $crawlResults = $this->crawler->filter('.functions-all__item')->each(function (Crawler $item) {
            $title = $item->filter('h4')->text();
            $description = $item->filter('p')->text();
            return new CrawlResult($title, $description);
        });

        // Create index if not exists
        $this->elasticManager->createIndex();

        // Save crawl result
        $this->elasticManager->bulkInsert($crawlResults);

        //foreach ($this->elasticManager->searchAll()['hits']['hits'] as $hit) {
        //    print_r($hit['_source']);
        //}

        // Remove index
        //$this->elasticManager->removeIndex();
    }
}