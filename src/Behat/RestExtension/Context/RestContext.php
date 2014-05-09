<?php

namespace Behat\RestExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\RestExtension\Differ\Differ;
use Behat\RestExtension\HttpClient\HttpClient;
use Behat\RestExtension\Message\Request;
use Behat\RestExtension\Message\RequestParser;
use Behat\RestExtension\Message\Response;

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
     * @var RequestParser
     */
    private $requestParser;

    /**
     * @var Differ
     */
    private $differ;

    /**
     * @param string        $baseUrl
     * @param HttpClient    $httpClient
     * @param RequestParser $requestParser
     * @param Differ        $differ
     */
    public function __construct($baseUrl, HttpClient $httpClient, RequestParser $requestParser, Differ $differ)
    {
        $this->baseUrl = $baseUrl;
        $this->httpClient = $httpClient;
        $this->requestParser = $requestParser;
        $this->differ = $differ;
    }

    /**
     * @When /^(?:|the )client requests (?P<method>GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS) "(?P<resource>[^"]*)"$/
     */
    public function theClientRequests($method, $resource)
    {
        $this->send(new Request($method, $this->baseUrl.$resource));
    }

    /**
     * @When /^(?:|the )client requests (?P<method>POST|PUT|OPTIONS) "(?P<resource>[^"]*)" with:$/
     */
    public function theClientRequestsWith($method, $resource, PyStringNode $content = null)
    {
        $request = new Request($method, $this->baseUrl.$resource);
        $this->requestParser->parse((string) $content, $request);

        $this->send($request);
    }

    /**
     * @Then /^(?:|the )response should be (?:|a )(?P<statusCode>[0-9]{3}) with json:$/
     */
    public function theResponseShouldBe($statusCode, PyStringNode $content)
    {
        $response = $this->httpClient->getLastResponse();

        if (null === $response) {
            throw new \LogicException('No request was made');
        }

        if ((int) $statusCode !== $response->getStatusCode()) {
            throw new \LogicException(sprintf('Expected %d status code but %d received', $statusCode, $response->getStatusCode()));
        }

        if ($diff = $this->differ->diff($response->getContent(), $content)) {
            throw new \LogicException($diff);
        }
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    private function send(Request $request)
    {
        return $this->httpClient->send($request);
    }
}
