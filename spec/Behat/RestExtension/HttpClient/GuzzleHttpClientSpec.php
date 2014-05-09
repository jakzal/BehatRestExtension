<?php

namespace spec\Behat\RestExtension\HttpClient;

require_once __DIR__.'/HttpClientBehavior.php';

use Behat\RestExtension\HttpClient\Exception\UnsupportedHttpMethodException;
use Behat\RestExtension\Message\Request;
use Guzzle\Http\Client as Guzzle;
use Guzzle\Http\Message\Header\HeaderCollection;
use Guzzle\Http\Message\Response as GuzzleResponse;
use Guzzle\Http\Message\RequestInterface as GuzzleRequest;
use Prophecy\Argument;

class GuzzleHttpClientSpec extends HttpClientBehavior
{
    function let(Guzzle $guzzle, GuzzleRequest $guzzleRequest, GuzzleResponse $guzzleResponse, HeaderCollection $headers)
    {
        $this->beConstructedWith($guzzle);

        $guzzleRequest->send()->willReturn($guzzleResponse);

        $guzzleResponse->getStatusCode()->willReturn(200);
        $guzzleResponse->getBody(true)->willReturn('Body');
        $guzzleResponse->getHeaders()->willReturn($headers);

        $headers->toArray()->willReturn(array('Content-Type' => 'application/json'));
    }

    function it_is_an_http_client()
    {
        $this->shouldHaveType('Behat\RestExtension\HttpClient\HttpClient');
    }

    function it_sends_a_request(Guzzle $guzzle, GuzzleRequest $guzzleRequest)
    {
        $guzzle->get('/events', array())->willReturn($guzzleRequest);

        $response = $this->send(new Request('GET', '/events'));

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }

    function it_sends_a_request_with_content(Guzzle $guzzle, GuzzleRequest $guzzleRequest)
    {
        $guzzle->post('/events', array(), 'request body')->willReturn($guzzleRequest);

        $response = $this->send(new Request('POST', '/events', 'request body'));

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }


    function it_throws_an_exception_if_method_is_not_supported()
    {
        $request = new Request('FOO', 200);

        $this->shouldThrow(new UnsupportedHttpMethodException('Unsupported or invalid http method: "FOO"'))->duringSend($request);
    }

    function it_makes_the_last_response_available(Guzzle $guzzle, GuzzleRequest $guzzleRequest)
    {
        $guzzle->get('/events', array())->willReturn($guzzleRequest);

        $response = $this->send(new Request('GET', '/events'));

        $this->getLastResponse()->shouldReturn($response);
    }
}
