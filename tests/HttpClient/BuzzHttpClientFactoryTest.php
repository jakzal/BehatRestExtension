<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use Http\Adapter\Buzz\Client;
use Http\Message\MessageFactory;

/**
 * @group integration
 */
class BuzzHttpClientFactoryTest extends HttpClientTestCase
{
    /**
     * @return HttpClientFactory
     */
    protected function createHttpClientFactory()
    {
        return new BuzzHttpClientFactory($this->prophesize(MessageFactory::class)->reveal());
    }

    public function test_it_creates_the_buzz_client()
    {
        $this->assertInstanceOf(Client::class, $this->httpClientFactory->createClient());
    }
}
