<?php

namespace spec\Behat\RestExtension\Differ;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SimpleJsonDifferSpec extends ObjectBehavior
{
    function it_is_a_differ()
    {
        $this->shouldHaveType('Behat\RestExtension\Differ\Differ');
    }

    function it_returns_null_for_the_same_strings()
    {
        $this->diff('[1]', '[1]')->shouldReturn(null);
    }

    function it_returns_a_message_with_bot_strings_pretty_formatted()
    {
        $message = 'Expected to get "{
    "b": 2
}" but received: "{
    "a": 1
}"';

        $this->diff('{"a": 1}', '{"b": 2}')->shouldReturn($message);
    }
}
