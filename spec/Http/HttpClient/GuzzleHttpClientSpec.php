<?php

namespace spec\Zalas\Behat\RestExtension\Http\HttpClient;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
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

    function it_returns_a_response_if_guzzle_throws_a_request_exception_but_response_is_present(Guzzle $guzzle, RequestInterface $request, ResponseInterface $response)
    {
        $exception = new RequestException('Request failed', $request->getWrappedObject(), $response->getWrappedObject());

        $guzzle->send($request)->willThrow($exception);

        $this->send($request)->shouldReturn($response);
    }

    function it_rethrows_the_request_exception_if_response_is_not_present(Guzzle $guzzle, RequestInterface $request)
    {
        $exception = new RequestException('Request failed', $request->getWrappedObject());

        $guzzle->send($request)->willThrow($exception);

        $this->shouldThrow($exception)->duringSend($request);
    }
}
