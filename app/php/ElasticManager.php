<?php
/**
 * Copyright (c) 2021 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.digital, jz@strategio.digital)
 */
declare(strict_types=1);

namespace App;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticManager
{
    const INDEX_NAME = 'functions_via_php';

    /**
     * Elastic client service
     * @var Client
     */
    protected Client $client;

    /**
     * ElasticManager constructor.
     */
    public function __construct()
    {
        $this->client = $this->getClientBuilder()->build();
    }

    /**
     * @param CrawlResult[] $crawlResults
     */
    public function bulkInsert(array $crawlResults) : void
    {
        $params = ['body' => []];

        foreach ($crawlResults as $result) {

            $params['body'][] = [
                'index' => [
                    '_index' => self::INDEX_NAME,
                ]
            ];

            $params['body'][] = [
                'title' => $result->getTitle(),
                'description' => $result->getDescription()
            ];
        }

        $this->client->bulk($params);
    }

    /**
     * Find all items in current index
     * @return array|callable
     */
    public function searchAll() : array|callable
    {
        $params = [
            'index' => self::INDEX_NAME,
            'body' => [
                'query' => [
                    'match_all' => new \stdClass
                ]
            ]
        ];

        return $this->client->search($params);
    }

    /**
     * Create index if not exists
     */
    public function createIndex() : void
    {
        $params = [
            'index' => self::INDEX_NAME
        ];

        if (!$this->client->indices()->exists($params)) {
            $this->client->indices()->create($params);
        }
    }

    /**
     * Remove index
     */
    public function removeIndex() : void
    {
        $params = [
            'index' => self::INDEX_NAME
        ];

        $this->client->indices()->delete($params);
    }

    /**
     * Setup elastic-connection
     * @return ClientBuilder
     */
    protected function getClientBuilder() : ClientBuilder
    {
        $hosts = [
            [
                'host' => $_ENV['ELASTIC_HOST'],
                'port' => $_ENV['ELASTIC_PORT'],
                'scheme' => $_ENV['ELASTIC_SCHEME'],
                'user' => $_ENV['ELASTIC_USER'],
                'pass' => $_ENV['ELASTIC_PASS']
                //'path' => '/elastic',
            ],
        ];

        return ClientBuilder::create()->setHosts($hosts);
    }
}