<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

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
        $output = $this->behatRunnerContext->getFullOutput();

        if (!preg_match('#\[DEBUG\]\[HTTP CLIENT\] (\w+)#', $output, $matches)) {
            throw new \LogicException('Could not determine which http client was used during the scenario.');
        }

        if ($matches[1] !== $client) {
            throw new \LogicException(sprintf(
                'The "%s" http client was used instead of the expected "%s" to run the scenario.',
                $matches[1],
                $client
            ));
        }
    }
}
