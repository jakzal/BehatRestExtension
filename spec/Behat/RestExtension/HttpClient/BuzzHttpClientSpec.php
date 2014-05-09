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

        $buzzResponse->getContent()->willReturn('Body');
        $buzzResponse->getStatusCode()->willReturn(200);
        $buzzResponse->getHeaders()->willReturn(array('Content-Type' => 'application/json'));
    }

    function it_is_a_rest_http_client()
    {
        $this->shouldHaveType('Behat\RestExtension\HttpClient\HttpClient');
    }

    function it_returns_a_response_on_get_request(Browser $browser, Response $buzzResponse)
    {
        $browser->get('/events', array())->willReturn($buzzResponse);

        $response = $this->get('/events');

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }


    function it_returns_a_response_on_head_request(Browser $browser, Response $buzzResponse)
    {
        $browser->head('/events', array())->willReturn($buzzResponse);

        $response = $this->head('/events');

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }

    function it_returns_a_response_on_post_request(Browser $browser, Response $buzzResponse)
    {
        $browser->post('/events', array('Accept' => 'application/json'), 'content')->willReturn($buzzResponse);

        $response = $this->post('/events', array('Accept' => 'application/json'), 'content');

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }

    function it_returns_a_response_on_put_request(Browser $browser, Response $buzzResponse)
    {
        $browser->put('/events/1', array('Accept' => 'application/json'), 'content')->willReturn($buzzResponse);

        $response = $this->put('/events/1', array('Accept' => 'application/json'), 'content');

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }

    function it_returns_a_response_on_patch_request(Browser $browser, Response $buzzResponse)
    {
        $browser->patch('/events/1', array('Accept' => 'application/json'), 'content')->willReturn($buzzResponse);

        $response = $this->patch('/events/1', array('Accept' => 'application/json'), 'content');

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }

    function it_returns_a_response_on_delete_request(Browser $browser, Response $buzzResponse)
    {
        $browser->delete('/events/1', array('Accept' => 'application/json'), 'content')->willReturn($buzzResponse);

        $response = $this->delete('/events/1', array('Accept' => 'application/json'), 'content');

        $response->shouldBeAResponse('Body', 200, array('Content-Type' => 'application/json'));
    }

    function it_makes_the_last_response_available(Browser $browser, Response $buzzResponse)
    {
        $browser->get('/events', array())->willReturn($buzzResponse);

        $response = $this->get('/events');

        $this->getLastResponse()->shouldReturn($response);
    }

    public function getMatchers()
    {
        return array(
            'beAResponse' => function ($response, $content, $statusCode, $headers) {
                if (!$response instanceof \Behat\RestExtension\Message\Response) {
                    return false;
                }

                if (!$response->getContent() === $content || !$response->getStatusCode() === $statusCode) {
                    return false;
                }

                $headersMatch = true;
                foreach ($headers as $name => $value) {
                    $headersMatch = $headersMatch && $response->getHeader($name) === $value;
                }

                return $headersMatch;
            }
        );
    }
}
