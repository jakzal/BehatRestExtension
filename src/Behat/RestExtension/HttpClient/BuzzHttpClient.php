<?php

namespace Behat\RestExtension\HttpClient;

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
     * @param string $resource
     * @param array  $headers
     *
     * @return Response
     */
    public function get($resource, array $headers = array())
    {
        $buzzResponse = $this->browser->get($resource, $headers);

        return $this->lastResponse = $this->createResponse($buzzResponse);
    }

    /**
     * @param string $resource
     * @param array  $headers
     *
     * @return Response
     */
    public function head($resource, array $headers = array())
    {
    }

    /**
     * @param string $resource
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function post($resource, array $headers = array(), $content = null)
    {
        $buzzResponse = $this->browser->post($resource, $headers, $content);

        return $this->lastResponse = $this->createResponse($buzzResponse);
    }

    /**
     * @param string $resource
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function put($resource, array $headers = array(), $content = null)
    {
    }

    /**
     * @param string $resource
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function patch($resource, array $headers = array(), $content = null)
    {
    }

    /**
     * @param string $resource
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function delete($resource, array $headers = array(), $content = null)
    {
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
