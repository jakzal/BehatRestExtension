<?php

namespace spec\Behat\RestExtension\Differ;

use Coduo\PHPMatcher\Matcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CoduoDifferSpec extends ObjectBehavior
{
    function let(Matcher $matcher)
    {
        $this->beConstructedWith($matcher);

        $matcher->getError()->willReturn(null);
    }

    function it_is_a_differ()
    {
        $this->shouldHaveType('Behat\RestExtension\Differ\Differ');
    }

    function it_returns_null_when_there_is_a_match(Matcher $matcher)
    {
        $matcher->match('[1]', '[@integer@]')->willReturn(true);

        $this->diff('[1]', '[@integer@]')->shouldReturn(null);
    }

    function it_returns_an_error_if_there_is_no_match(Matcher $matcher)
    {
        $errorMessage = '"a" did not match "@integer@"';

        $matcher->match('["a"]', '[@integer@]')->willReturn(false);
        $matcher->getError()->willReturn($errorMessage);

        $this->diff('["a"]', '[@integer@]')->shouldReturn($errorMessage);
    }
}
