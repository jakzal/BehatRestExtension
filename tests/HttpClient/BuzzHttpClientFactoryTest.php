<?php

namespace Zalas\Behat\RestExtension\HttpClient;

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
}
