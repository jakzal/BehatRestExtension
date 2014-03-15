<?php

namespace spec\Behat\RestExtension\Context\Argument;

use Behat\RestExtension\HttpClient\HttpClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpClientResolverSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_an_argument_resolver()
    {
        $this->shouldHaveType('Behat\Behat\Context\Argument\ArgumentResolver');
    }

    function it_returns_arguments_directly_if_class_does_not_have_a_constructor(\ReflectionClass $classReflection, \ReflectionParameter $parameter)
    {
        $this->resolveArguments($classReflection, array($parameter))->shouldReturn(array($parameter));
    }

    function it_replaces_an_http_client_argument(\ReflectionClass $class, \ReflectionMethod $constructor, \ReflectionParameter $anyParameter, \ReflectionParameter $httpClientParameter, \ReflectionClass $parameterClass, HttpClient $httpClient)
    {
        $class->getConstructor()->willReturn($constructor);
        $constructor->getParameters()->willReturn(array($anyParameter, $httpClientParameter));
        $httpClientParameter->getClass()->willReturn($parameterClass);

        $parameterClass->implementsInterface('Behat\RestExtension\HttpClient\HttpClient')->willReturn(true);

        $this->resolveArguments($class, array())->shouldReturn(array(1 => $httpClient));
    }
}
