<?php

namespace spec\Behat\RestExtension\Message;

use Behat\RestExtension\Message\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestParserSpec extends ObjectBehavior
{
    function it_parses_the_message_headers_alone(Request $request)
    {
        $message = <<<MESSAGE
Accept: text/html
Host: localhost
MESSAGE;

        $request->addHeader('Accept', 'text/html')->shouldBeCalled();
        $request->addHeader('Host', 'localhost')->shouldBeCalled();

        $this->parse($message, $request);
    }

    function it_parses_the_message_headers_and_body(Request $request)
    {
        $message = <<<MESSAGE
Accept: text/html
Host: localhost

[ { "name": "SymfonyCon" } ]
MESSAGE;

        $request->addHeader('Accept', 'text/html')->shouldBeCalled();
        $request->addHeader('Host', 'localhost')->shouldBeCalled();
        $request->setBody('[ { "name": "SymfonyCon" } ]')->shouldBeCalled();

        $this->parse($message, $request);
    }

    function it_parses_a_message_body_which_contains_a_colon(Request $request)
    {
        $message = '[ { "name": "SymfonyCon" } ]';

        $request->setBody($message)->shouldBeCalled();

        $this->parse($message, $request);
    }
}
