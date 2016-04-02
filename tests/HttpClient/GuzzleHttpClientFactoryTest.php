<?php

namespace Zalas\Behat\RestExtension\HttpClient;
use Http\Adapter\Guzzle6\Client;

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
        return new GuzzleHttpClientFactory(['base_url' => 'http://localhost']);
    }

    public function test_it_creates_the_guzzle_client()
    {
        $this->assertInstanceOf(Client::class, $this->httpClientFactory->createClient());
    }
}
