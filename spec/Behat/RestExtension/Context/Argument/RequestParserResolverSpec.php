<?php

namespace spec\Behat\RestExtension\Context\Argument;

use Behat\RestExtension\Message\RequestParser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestParserResolverSpec extends ObjectBehavior
{
    function let(RequestParser $requestParser)
    {
        $this->beConstructedWith($requestParser);
    }

    function it_is_an_argument_resolver()
    {
        $this->shouldHaveType('Behat\Behat\Context\Argument\ArgumentResolver');
    }

    function it_returns_arguments_directly_if_class_does_not_have_a_constructor(\ReflectionClass $classReflection, \ReflectionParameter $parameter)
    {
        $this->resolveArguments($classReflection, array($parameter))->shouldReturn(array($parameter));
    }

    function it_replaces_a_request_parser_argument(\ReflectionClass $class, \ReflectionMethod $constructor, \ReflectionParameter $anyParameter, \ReflectionParameter $requestParserParameter, \ReflectionClass $parameterClass, RequestParser $requestParser)
    {
        $class->getConstructor()->willReturn($constructor);
        $constructor->getParameters()->willReturn(array($anyParameter, $requestParserParameter));
        $requestParserParameter->getClass()->willReturn($parameterClass);

        $parameterClass->getName()->willReturn('Behat\RestExtension\Message\RequestParser');

        $this->resolveArguments($class, array())->shouldReturn(array(1 => $requestParser));
    }
}
