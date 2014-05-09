<?php

namespace Behat\RestExtension\HttpClient;

use Behat\RestExtension\HttpClient\Exception\UnsupportedHttpMethodException;
use Behat\RestExtension\Message\Request;
use Buzz\Browser;
use Buzz\Message\Response as BuzzResponse;
use Behat\RestExtension\Message\Response;

class BuzzHttpClient implements HttpClient
{
    /**
     * @var Browser
     */
    private $browser;

    /**
     * @var Response|null
     */
    private $lastResponse;

    /**
     * @param Browser $browser
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function send(Request $request)
    {
        $method = strtolower($request->getMethod());

        if (!method_exists($this->browser, $method)) {
            throw new UnsupportedHttpMethodException(sprintf('Unsupported or invalid http method: "%s"', $request->getMethod()));
        }

        if (in_array($method, array('get', 'head'))) {
            $buzzResponse = $this->browser->$method($request->getResource(), $request->getHeaders());
        } else {
            $buzzResponse = $this->browser->$method($request->getResource(), $request->getHeaders(), $request->getBody());
        }

        return $this->lastResponse = $this->createResponse($buzzResponse);
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param BuzzResponse $buzzResponse
     *
     * @return Response
     */
    private function createResponse(BuzzResponse $buzzResponse)
    {
        return new Response($buzzResponse->getContent(), $buzzResponse->getStatusCode(), $buzzResponse->getHeaders());
    }
}
