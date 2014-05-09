<?php

namespace Behat\RestExtension\HttpClient;

use Behat\RestExtension\HttpClient\Exception\UnsupportedHttpMethodException;
use Behat\RestExtension\Message\Request;
use Behat\RestExtension\Message\Response;
use Guzzle\Http\Client as Guzzle;

class GuzzleHttpClient implements HttpClient
{
    /**
     * @var Guzzle
     */
    private $guzzle;

    /**
     * @var Response|null
     */
    private $lastResponse;

    /**
     * @param Guzzle $guzzle
     */
    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function send(Request $request)
    {
        $method = strtolower($request->getMethod());

        if (!method_exists($this->guzzle, $method)) {
            throw new UnsupportedHttpMethodException(sprintf('Unsupported or invalid http method: "%s"', $request->getMethod()));
        }

        if (in_array($method, array('get', 'head'))) {
            $guzzleRequest = $this->guzzle->$method($request->getResource(), $request->getHeaders());
        } else {
            $guzzleRequest = $this->guzzle->$method($request->getResource(), $request->getHeaders(), $request->getBody());
        }

        return $this->lastResponse = $this->createResponse($guzzleRequest->send());
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param $guzzleResponse
     *
     * @return Response
     */
    private function createResponse($guzzleResponse)
    {
        return new Response(
            $guzzleResponse->getBody(true),
            $guzzleResponse->getStatusCode(),
            $guzzleResponse->getHeaders()->toArray()
        );
    }
}
