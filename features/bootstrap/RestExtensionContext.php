<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class RestExtensionContext implements Context
{
    /**
     * @Given a feature using an http client
     */
    public function aFeatureUsingAnHttpClient()
    {
        throw new PendingException();
    }

    /**
     * @Then the :client http client should have been used
     */
    public function theHttpClientShouldBeUsed($client)
    {
        throw new PendingException();
    }
}
