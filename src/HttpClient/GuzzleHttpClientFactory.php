<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use Http\Client\HttpClient;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as Adapter;

final class GuzzleHttpClientFactory implements HttpClientFactory
{
    /**
     * @param array $config
     *
     * @return HttpClient
     */
    public function createClient(array $config = [])
    {
        if (!class_exists(Adapter::class)) {
            throw new \RuntimeException('To use the Guzzle http client you need to install the "php-http/guzzle6-adapter" package.');
        }

        return new Adapter(new Client($config));
    }
}
