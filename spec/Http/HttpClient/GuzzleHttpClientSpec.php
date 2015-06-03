<?php

namespace spec\Zalas\Behat\RestExtension\Http\HttpClient;

use Guzzle\Http\ClientInterface as Guzzle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zalas\Behat\RestExtension\Http\HttpClient;

class GuzzleHttpClientSpec extends ObjectBehavior
{
    function let(Guzzle $guzzle)
    {
        $this->beConstructedWith($guzzle);
    }

    function it_is_an_http_client()
    {
        $this->shouldHaveType(HttpClient::class);
    }

    function it_proxies_requests_to_guzzle(Guzzle $guzzle, RequestInterface $request, ResponseInterface $response)
    {
        $guzzle->send($request)->willReturn($response);

        $this->send($request)->shouldReturn($response);
    }
}
