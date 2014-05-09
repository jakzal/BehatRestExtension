<?php

namespace spec\Behat\RestExtension\HttpClient;

require_once __DIR__.'/HttpClientBehavior.php';

use Behat\RestExtension\HttpClient\Exception\UnsupportedHttpMethodException;
use Behat\RestExtension\Message\Request;
use Buzz\Browser;
use Buzz\Message\Response as BuzzResponse;
use Prophecy\Argument;

class BuzzHttpClientSpec extends HttpClientBehavior
{
    function let(Browser $browser, BuzzResponse $buzzResponse)
    {
        $this->beConstructedWith($browser);

        $buzzResponse->getContent()->willReturn('Body');
        $buzzResponse->getStatusCode()->willReturn(200);
        $buzzResponse->getHeaders()->willReturn(array('Content-Type' => 'application/json'));
    }

    function it_is_a_rest_http_client()
    {
        $this->shouldHaveType('Behat\RestExtension\HttpClient\HttpClient');
    }

    function it_sends_a_request(Browser $browser, BuzzResponse $buzzResponse)
    {
        $browser->get('/events', array())->willReturn($buzzResponse);

        $response = $this->send(new Request('GET', '/events'));

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }

    function it_sends_a_request_with_body(Browser $browser, BuzzResponse $buzzResponse)
    {
        $browser->post('/events', array(), 'request body')->willReturn($buzzResponse);

        $request = new Request('POST', '/events', 'request body');

        $response = $this->send($request);

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }

    function it_throws_an_exception_if_method_is_not_supported()
    {
        $request = new Request('FOO', 200);

        $this->shouldThrow(new UnsupportedHttpMethodException('Unsupported or invalid http method: "FOO"'))->duringSend($request);
    }

    function it_makes_the_last_response_available(Browser $browser, BuzzResponse $buzzResponse)
    {
        $browser->get('/events', array())->willReturn($buzzResponse);

        $response = $this->send(new Request('GET', '/events'));

        $this->getLastResponse()->shouldReturn($response);
    }
}
