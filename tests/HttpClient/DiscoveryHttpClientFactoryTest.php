<?php

namespace Zalas\Behat\RestExtension\HttpClient;

/**
 * @group integration
 */
class DiscoveryHttpClientFactoryTest extends HttpClientTestCase
{
    /**
     * @return HttpClientFactory
     */
    protected function createHttpClientFactory()
    {
        return new DiscoveryHttpClientFactory();
    }
}
