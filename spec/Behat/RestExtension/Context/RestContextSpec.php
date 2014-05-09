<?php

namespace spec\Behat\RestExtension\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\RestExtension\Differ\Differ;
use Behat\RestExtension\HttpClient\HttpClient;
use Behat\RestExtension\Message\Request;
use Behat\RestExtension\Message\RequestParser;
use Behat\RestExtension\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RestContextSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, RequestParser $requestParser, Response $response, Differ $differ)
    {
        $this->beConstructedWith('http://localhost', $httpClient, $requestParser, $differ);

        $httpClient->getLastResponse()->willReturn($response);
        $response->getStatusCode()->willReturn(200);
    }

    function it_is_a_behat_context()
    {
        $this->shouldHaveType('Behat\Behat\Context\Context');
    }

    function it_forwards_requests_to_the_http_client(HttpClient $httpClient)
    {
        $httpClient->send(new Request('GET', 'http://localhost/events'))->shouldBeCalled();

        $this->theClientRequests('GET', '/events');
    }

    function it_forwards_requests_with_body_to_the_http_client(HttpClient $httpClient, RequestParser $requestParser, PyStringNode $body)
    {
        $body->__toString()->willReturn('[]');
        $requestParser->parse('[]', Argument::type('Behat\RestExtension\Message\Request'))->will(
            function ($arguments) {
                list($message, $request) = $arguments;
                $request->setBody($message);
            }
        );

        $httpClient->send(new Request('POST', 'http://localhost/events', '[]'))->shouldBeCalled();

        $this->theClientRequestsWith('POST', '/events', $body);
    }

    function it_throws_an_exception_if_expected_status_code_does_not_match_the_response(Response $response, PyStringNode $body)
    {
        $response->getStatusCode()->willReturn(201);

        $this->shouldThrow(new \LogicException('Expected 200 status code but 201 received'))
            ->duringTheResponseShouldBe(200, $body);
    }

    function it_throws_an_exception_if_there_was_no_request_made(HttpClient $httpClient, PyStringNode $body)
    {
        $httpClient->getLastResponse()->willReturn(null);

        $this->shouldThrow(new \LogicException('No request was made'))
            ->duringTheResponseShouldBe(200, $body);
    }

    function it_throws_an_exception_if_the_expected_response_is_different_to_the_actual(Response $response, PyStringNode $body, Differ $differ)
    {
        $bodyPattern = '1';
        $returnedBody = '2';

        $body->__toString()->willReturn($bodyPattern);
        $response->getBody()->willReturn($returnedBody);

        $differ->diff($returnedBody, $bodyPattern)->willReturn('--');

        $this->shouldThrow(new \LogicException('--'))
            ->duringTheResponseShouldBe(200, $body);
    }
}
