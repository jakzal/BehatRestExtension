<?php

namespace Zalas\Behat\RestExtension\Http\HttpClient;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zalas\Behat\RestExtension\Http\HttpClient;

class GuzzleHttpClient implements HttpClient
{
    /**
     * @var Guzzle
     */
    private $guzzle;

    /**
     * @param Guzzle $guzzle
     */
    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        try {
            return $this->guzzle->send($request);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }

            throw $e;
        }
    }
}
