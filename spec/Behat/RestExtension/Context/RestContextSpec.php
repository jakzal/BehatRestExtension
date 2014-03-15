<?php

namespace spec\Behat\RestExtension\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\RestExtension\HttpClient\HttpClient;
use Behat\RestExtension\Message\RequestParser;
use Behat\RestExtension\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RestContextSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, RequestParser $requestParser, Response $response)
    {
        $this->beConstructedWith('http://localhost', $httpClient, $requestParser);

        $httpClient->getLastResponse()->willReturn($response);
    }

    function it_is_a_behat_context()
    {
        $this->shouldHaveType('Behat\Behat\Context\Context');
    }

    function it_forwards_requests_to_the_http_client(HttpClient $httpClient)
    {
        $httpClient->get('http://localhost/events')->shouldBeCalled();

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

        $httpClient->post('http://localhost/events', array(), '[]')->shouldBeCalled();

        $this->theClientRequestsWith('POST', '/events', $body);
    }

    function it_throws_an_exception_if_expected_status_code_does_not_match_the_response(Response $response, PyStringNode $content)
    {
        $response->getStatusCode()->willReturn(201);

        $this->shouldThrow(new \LogicException('Expected 200 status code but 201 received'))
            ->duringTheResponseShouldBeJson(200, $content);
    }

    function it_throws_an_exception_if_there_was_no_request_made(HttpClient $httpClient, PyStringNode $content)
    {
        $httpClient->getLastResponse()->willReturn(null);

        $this->shouldThrow(new \LogicException('No request was made'))
            ->duringTheResponseShouldBeJson(200, $content);
    }
}
