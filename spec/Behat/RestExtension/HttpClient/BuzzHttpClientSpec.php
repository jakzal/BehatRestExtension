<?php

namespace spec\Behat\RestExtension\HttpClient;

use Buzz\Browser;
use Buzz\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BuzzHttpClientSpec extends ObjectBehavior
{
    function let(Browser $browser, Response $buzzResponse)
    {
        $this->beConstructedWith($browser);
    }

    function it_is_a_rest_http_client()
    {
        $this->shouldHaveType('Behat\RestExtension\HttpClient\HttpClient');
    }

    function it_returns_a_response(Browser $browser, Response $buzzResponse)
    {
        $buzzResponse->getContent()->willReturn('Body');
        $buzzResponse->getStatusCode()->willReturn(200);
        $buzzResponse->getHeaders()->willReturn(array('Content-Type' => 'application/json'));

        $browser->get('/events', array())->willReturn($buzzResponse);

        $response = $this->get('/events');
        $response->shouldBeAnInstanceOf('Behat\RestExtension\HttpClient\Response');
        $response->getContent()->shouldReturn('Body');
        $response->getStatusCode()->shouldReturn(200);
        $response->getHeader('Content-Type')->shouldReturn('application/json');
    }

    function it_stores_the_last_response(Browser $browser, Response $buzzResponse)
    {
        $buzzResponse->getContent()->willReturn('Body');
        $buzzResponse->getStatusCode()->willReturn(200);
        $buzzResponse->getHeaders()->willReturn(array('Content-Type' => 'application/json'));

        $browser->get('/events', array())->willReturn($buzzResponse);

        $response = $this->get('/events');

        $this->getLastResponse()->shouldReturn($response);
    }
}
