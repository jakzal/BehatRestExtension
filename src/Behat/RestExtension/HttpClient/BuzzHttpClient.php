<?php

namespace Behat\RestExtension\HttpClient;

use Buzz\Browser;

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

        $this->lastResponse = new Response($buzzResponse->getContent(), $buzzResponse->getStatusCode(), $buzzResponse->getHeaders());

        return $this->lastResponse;
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
}
