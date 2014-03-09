<?php

namespace Behat\RestExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\RestExtension\HttpClient\BuzzHttpClient;
use Behat\RestExtension\HttpClient\HttpClient;
use Buzz\Browser;

class RestContext implements Context
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param string     $baseUrl
     * @param HttpClient $httpClient
     */
    public function __construct($baseUrl = null, HttpClient $httpClient = null)
    {
        // @todo implement an argument resolver and inject the baseUrl
        $this->baseUrl = $baseUrl ? $baseUrl : 'http://localhost:8000';
        $this->httpClient = $httpClient ? $httpClient : new BuzzHttpClient(new Browser());
    }

    /**
     * @When /^(?:|the )client requests (?P<method>GET|HEAD|POST|PUT|DELETE|OPTIONS) "(?P<resource>[^"]*)"$/
     */
    public function theClientRequests($method, $resource)
    {
        $method = strtolower($method);

        $this->httpClient->$method($this->baseUrl.$resource);
    }

    /**
     * @Then /^(?:|the )response should be (?:|a )(?P<statusCode>[0-9]{3}) with json:$/
     */
    public function theResponseShouldBeJson($statusCode, PyStringNode $content)
    {
        $response = $this->httpClient->getLastResponse();

        if ((int) $statusCode !== $response->getStatusCode()) {
            throw new \LogicException(sprintf('Expected %d status code but %d received', $statusCode, $response->getStatusCode()));
        }

        // @todo introduce a differ
        $receivedJson = json_decode($response->getContent());
        $expectedJson = json_decode($content);

        if ($receivedJson != $expectedJson) {
            $message = sprintf('Expected to get "%s" but received: "%s"', json_encode($expectedJson, JSON_PRETTY_PRINT), json_encode($receivedJson, JSON_PRETTY_PRINT));

            throw new \LogicException($message);
        }
    }
}
