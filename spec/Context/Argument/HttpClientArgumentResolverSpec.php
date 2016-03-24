<?php

namespace spec\Zalas\Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use Http\Client\HttpClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class HttpClientArgumentResolverSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_an_argument_resolver()
    {
        $this->shouldHaveType(ArgumentResolver::class);
    }

    function it_returns_arguments_directly_if_a_class_does_not_have_a_constructor(ReflectionClass $class, ReflectionParameter $parameter)
    {
        $class->getConstructor()->willReturn(null);

        $this->resolveArguments($class, [$parameter])->shouldReturn([$parameter]);
    }

    function it_resolves_an_argument_by_interface(ReflectionClass $class, ReflectionMethod $constructor, ReflectionParameter $anyParameter, ReflectionParameter $httpClientParameter, ReflectionClass $parameterClass, HttpClient $httpClient)
    {
        $class->getConstructor()->willReturn($constructor);
        $constructor->getParameters()->willReturn([$anyParameter, $httpClientParameter]);
        $httpClientParameter->getClass()->willReturn($parameterClass);
        $parameterClass->getName()->willReturn(HttpClient::class);

        $this->resolveArguments($class, [])->shouldReturn([1 => $httpClient]);
    }
}
