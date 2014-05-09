<?php

namespace Behat\RestExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\RestExtension\HttpClient\HttpClient;

class RestDebugContext implements Context
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
     * @AfterStep
     */
    public function debugLastResponse(AfterStepScope $scope)
    {
        if (!$scope->getTestResult()->isPassed() && $response = $this->httpClient->getLastResponse()) {
            throw new \Exception("Last response:\n".$response->getBody());
        }
    }
}
