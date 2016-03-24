<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;

final class DiscoveryHttpClientFactory implements HttpClientFactory
{
    /**
     * @param array $config
     *
     * @return HttpClient
     */
    public function createClient(array $config = [])
    {
        if (!class_exists(HttpClientDiscovery::class)) {
            throw new \RuntimeException('To use the discovery http client you need to install the "php-http/discovery" and the "puli/composer-plugin" packages.');
        }

        return HttpClientDiscovery::find();
    }
}
