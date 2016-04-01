<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;

class RestExtensionContext implements Context
{
    /**
     * @var BehatRunnerContext|null
     */
    private $behatRunnerContext;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->behatRunnerContext = $scope->getEnvironment()->getContext('BehatRunnerContext');
    }

    /**
     * @Given a feature using an http client
     */
    public function aFeatureUsingAnHttpClient()
    {
        $this->behatRunnerContext->givenBehatProject('features/fixtures/default');
    }

    /**
     * @Then the :client http client should have been used
     */
    public function theHttpClientShouldBeUsed($client)
    {
        throw new PendingException();
    }
}
