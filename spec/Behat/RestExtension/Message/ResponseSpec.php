<?php

namespace spec\Behat\RestExtension\Message;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResponseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Body', 200, array('Content-Type' => 'application/json'));
    }

    function it_exposes_the_status_code()
    {
        $this->getStatusCode()->shouldReturn(200);
    }

    function it_exposes_the_message_body()
    {
        $this->getBody()->shouldReturn('Body');
    }

    function it_exposes_the_headers()
    {
        $this->getHeader('Content-Type')->shouldReturn('application/json');
    }

    function it_returns_null_if_header_does_not_exist()
    {
        $this->getHeader('Cache-Control')->shouldReturn(null);
    }

    function it_returns_default_if_header_does_not_exist()
    {
        $this->getHeader('Cache-Control', 'private, no-cache')->shouldReturn('private, no-cache');
    }
}
