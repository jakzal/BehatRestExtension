<?php

namespace spec\Behat\RestExtension\Message;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('GET', '/');
    }

    function it_exposes_the_method()
    {
        $this->getMethod()->shouldReturn('GET');
    }

    function it_exposes_the_resource()
    {
        $this->getResource()->shouldReturn('/');
    }

    function it_exposes_the_body()
    {
        $this->setBody('[]');

        $this->getBody()->shouldReturn('[]');
    }

    function it_exposes_the_headers()
    {
        $this->addHeader('Host', 'localhost');

        $this->getHeader('Host')->shouldReturn('localhost');
        $this->getHeaders()->shouldReturn(array('Host' => 'localhost'));
    }

    function it_returns_null_if_header_does_not_exist()
    {
        $this->getHeader('Host')->shouldReturn(null);
    }

    function it_returns_default_if_header_does_not_exist()
    {
        $this->getHeader('Host', 'localhost')->shouldReturn('localhost');
    }
}
