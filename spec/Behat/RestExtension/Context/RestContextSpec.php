<?php

namespace spec\Behat\RestExtension\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\RestExtension\HttpClient\HttpClient;
use Behat\RestExtension\HttpClient\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RestContextSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, Response $response)
    {
        $this->beConstructedWith('http://localhost', $httpClient);

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

    function it_throws_an_exception_if_expected_status_code_does_not_match_the_response(Response $response, PyStringNode $content)
    {
        $response->getStatusCode()->willReturn(201);

        $this->shouldThrow(new \LogicException('Expected 200 status code but 201 received'))
            ->duringTheResponseShouldBeJson(200, $content);
    }
}
