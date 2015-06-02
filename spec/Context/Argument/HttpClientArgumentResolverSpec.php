<?php

namespace spec\Zalas\Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zalas\Behat\RestExtension\Http\HttpClient;

class HttpClientArgumentResolverSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_an_argument_resolver()
    {
        $this->shouldHaveType(ArgumentResolver::class);
    }
}
