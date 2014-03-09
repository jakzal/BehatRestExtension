<?php

namespace spec\Behat\RestExtension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    function it_is_a_testwork_extension()
    {
        $this->shouldHaveType('Behat\Testwork\ServiceContainer\Extension');
    }

    function it_is_named_rest()
    {
        $this->getConfigKey()->shouldReturn('rest');
    }
}
