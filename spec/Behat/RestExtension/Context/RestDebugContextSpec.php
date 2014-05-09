<?php

namespace spec\Behat\RestExtension\Context;

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\RestExtension\HttpClient\HttpClient;
use Behat\RestExtension\Message\Response;
use Behat\Testwork\Tester\Result\TestResult;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RestDebugContextSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_a_behat_context()
    {
        $this->shouldHaveType('Behat\Behat\Context\Context');
    }
}
