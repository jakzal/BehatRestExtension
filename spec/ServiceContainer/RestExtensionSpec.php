<?php

namespace spec\Zalas\Behat\RestExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RestExtensionSpec extends ObjectBehavior
{
    function it_is_a_testwork_extension()
    {
        $this->shouldHaveType(Extension::class);
    }

    function it_is_called_rest()
    {
        $this->getConfigKey()->shouldReturn('rest');
    }
}
