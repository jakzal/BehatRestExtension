<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use Http\Client\HttpClient;

/**
 * @group integration
 */
abstract class HttpClientTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClientFactory
     */
    protected $httpClientFactory;

    /**
     * @before
     */
    public function initialize()
    {
        $this->httpClientFactory = $this->createHttpClientFactory();
    }

    /**
     * @return HttpClientFactory
     */
    abstract protected function createHttpClientFactory();

    public function test_it_is_an_http_client_factory()
    {
        $this->assertInstanceOf(HttpClientFactory::class, $this->httpClientFactory);
    }

    public function test_it_creates_an_http_client()
    {
        $this->assertInstanceOf(HttpClient::class, $this->httpClientFactory->createClient());
    }
}
