<?php

namespace spec\Behat\RestExtension\Context\Argument;

use Behat\RestExtension\HttpClient\HttpClient;
use Behat\RestExtension\Message\RequestParser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurableArgumentResolverSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, RequestParser $requestParser, \ReflectionClass $class, \ReflectionMethod $constructor, \ReflectionClass $parameterClass)
    {
        $this->beConstructedWith(array(
            'Behat\RestExtension\HttpClient\HttpClient' => $httpClient,
            'Behat\RestExtension\Message\RequestParser' => $requestParser
        ));

        $class->getConstructor()->willReturn($constructor);
        $parameterClass->getName()->willReturn(null);
        $parameterClass->implementsInterface(Argument::any())->willReturn(false);
    }

    function it_is_an_argument_resolver()
    {
        $this->shouldHaveType('Behat\Behat\Context\Argument\ArgumentResolver');
    }

    function it_returns_arguments_directly_if_class_does_not_have_a_constructor(\ReflectionClass $class, \ReflectionParameter $parameter)
    {
        $class->getConstructor()->willReturn(null);

        $this->resolveArguments($class, array($parameter))->shouldReturn(array($parameter));
    }

    function it_resolves_an_argument_by_interface(\ReflectionClass $class, \ReflectionMethod $constructor, \ReflectionParameter $anyParameter, \ReflectionParameter $httpClientParameter, \ReflectionClass $parameterClass, HttpClient $httpClient)
    {
        $constructor->getParameters()->willReturn(array($anyParameter, $httpClientParameter));
        $httpClientParameter->getClass()->willReturn($parameterClass);
        $parameterClass->implementsInterface('Behat\RestExtension\HttpClient\HttpClient')->willReturn(true);

        $this->resolveArguments($class, array())->shouldReturn(array(1 => $httpClient));
    }

    function it_resolves_an_argument_by_class(\ReflectionClass $class, \ReflectionMethod $constructor, \ReflectionParameter $anyParameter, \ReflectionParameter $requestParserParameter, \ReflectionClass $parameterClass, RequestParser $requestParser)
    {
        $constructor->getParameters()->willReturn(array($anyParameter, $requestParserParameter));
        $requestParserParameter->getClass()->willReturn($parameterClass);

        $parameterClass->getName()->willReturn('Behat\RestExtension\Message\RequestParser');

        $this->resolveArguments($class, array())->shouldReturn(array(1 => $requestParser));
    }
}
