<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin\Fixtures;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class DummyHttpClient implements HttpClient
{
    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request)
    {
    }
}
