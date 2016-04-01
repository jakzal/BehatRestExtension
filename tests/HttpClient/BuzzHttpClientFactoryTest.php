<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use Http\Message\MessageFactory;

class BuzzHttpClientFactoryTest extends HttpClientTestCase
{
    /**
     * @return HttpClientFactory
     */
    protected function createHttpClientFactory()
    {
        return new BuzzHttpClientFactory($this->prophesize(MessageFactory::class)->reveal());
    }
}
