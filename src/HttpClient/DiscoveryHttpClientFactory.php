<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;

final class DiscoveryHttpClientFactory implements HttpClientFactory
{
    /**
     * @return HttpClient
     */
    public function createClient()
    {
        return HttpClientDiscovery::find();
    }
}
