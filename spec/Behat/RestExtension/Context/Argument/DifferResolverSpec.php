<?php

namespace spec\Behat\RestExtension\Context\Argument;

use Behat\RestExtension\Differ\Differ;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DifferResolverSpec extends ObjectBehavior
{
    function let(Differ $differ)
    {
        $this->beConstructedWith($differ);
    }

    function it_is_an_argument_resolver()
    {
        $this->shouldHaveType('Behat\Behat\Context\Argument\ArgumentResolver');
    }

    function it_returns_arguments_directly_if_class_does_not_have_a_constructor(\ReflectionClass $classReflection, \ReflectionParameter $parameter)
    {
        $this->resolveArguments($classReflection, array($parameter))->shouldReturn(array($parameter));
    }

    function it_replaces_a_differ_argument(\ReflectionClass $class, \ReflectionMethod $constructor, \ReflectionParameter $anyParameter, \ReflectionParameter $differParameter, \ReflectionClass $parameterClass, Differ $differ)
    {
        $class->getConstructor()->willReturn($constructor);
        $constructor->getParameters()->willReturn(array($anyParameter, $differParameter));
        $differParameter->getClass()->willReturn($parameterClass);

        $parameterClass->implementsInterface('Behat\RestExtension\Differ\Differ')->willReturn(true);

        $this->resolveArguments($class, array())->shouldReturn(array(1 => $differ));
    }
}
