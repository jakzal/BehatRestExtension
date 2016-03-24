<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as Adapter;
use Http\Client\HttpClient;

final class GuzzleHttpClientFactory implements HttpClientFactory
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return HttpClient
     */
    public function createClient()
    {
        return new Adapter(new Client($this->config));
    }
}
