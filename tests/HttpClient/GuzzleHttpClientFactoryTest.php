<?php

namespace Zalas\Behat\RestExtension\HttpClient;

/**
 * @group integration
 */
class GuzzleHttpClientFactoryTest extends HttpClientTestCase
{
    /**
     * @return HttpClientFactory
     */
    protected function createHttpClientFactory()
    {
        return new GuzzleHttpClientFactory();
    }
}
