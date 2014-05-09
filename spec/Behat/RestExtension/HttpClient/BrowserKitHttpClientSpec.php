<?php

namespace spec\Behat\RestExtension\HttpClient;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BrowserKitHttpClientSpec extends ObjectBehavior
{
    function it_is_an_http_client()
    {
        $this->shouldHaveType('Behat\RestExtension\HttpClient\HttpClient');
    }
}
