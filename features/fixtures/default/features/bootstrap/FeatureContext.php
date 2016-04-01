<?php

use Behat\Behat\Context\Context;
use GuzzleHttp\Psr7\Request;
use Http\Adapter\Buzz\Client as BuzzAdapter;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Client\HttpClient;
use PHPUnit_Framework_Assert as PHPUnit;
use Psr\Http\Message\ResponseInterface;

class FeatureContext implements Context
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Echoes the http client name so it could be inspected.
     *
     * @BeforeScenario
     */
    public function debugHttpClient()
    {
        $clients = [
            BuzzAdapter::class => 'buzz',
            GuzzleAdapter::class => 'guzzle',
        ];

        $class = get_class($this->httpClient);

        $name = isset($clients[$class]) ? $clients[$class] : 'unknown';

        echo '[DEBUG][HTTP CLIENT] '.$name."\n";
    }

    /**
     * @When I search for :postcode
     */
    public function iSearchFor($postcode)
    {
        $uri = sprintf('http://localhost:8000/postcodes/%s', $postcode);

        $this->lastResponse = $this->httpClient->sendRequest(new Request('GET', $uri));
    }

    /**
     * @Then I should see its location
     */
    public function iShouldSeeItsLocation()
    {
        PHPUnit::assertInstanceOf(ResponseInterface::class, $this->lastResponse);
        PHPUnit::assertSame(200, $this->lastResponse->getStatusCode(), 'Got a successful response');

        $json = json_decode($this->lastResponse->getBody(), true);
        PHPUnit::assertInternalType('array', $json, 'Response contains a query result');
        PHPUnit::arrayHasKey('result', $json, 'Result found in the response');
        PHPUnit::assertArrayHasKey('latitude', $json['result'], 'Latitude found in the response');
        PHPUnit::assertArrayHasKey('longitude', $json['result'], 'Longitude found in the response');
        PHPUnit::assertInternalType('double', $json['result']['latitude'], 'Latitude is a double');
        PHPUnit::assertInternalType('double', $json['result']['longitude'], 'Longitude is a double');
    }

    /**
     * @Then I should be informed the postcode was not found
     */
    public function iShouldBeInformedThePostcodeWasNotFound()
    {
        PHPUnit::assertSame(404, $this->lastResponse->getStatusCode(), '404 Not Found');
    }
}
