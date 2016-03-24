<?php

namespace Zalas\Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use Http\Message\MessageFactory;
use Zalas\Behat\RestExtension\Context\Argument\Fixtures\ClassWithMessageFactoryArgument;
use Zalas\Behat\RestExtension\Context\Argument\Fixtures\ClassWithNoMessageFactoryArgument;

class MessageFactoryArgumentResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageFactoryArgumentResolver
     */
    private $argumentResolver;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    protected function setUp()
    {
        $this->messageFactory = $this->prophesize(MessageFactory::class)->reveal();
        $this->argumentResolver = new MessageFactoryArgumentResolver($this->messageFactory);
    }

    public function test_it_is_an_argument_resolver()
    {
        $this->assertInstanceOf(ArgumentResolver::class, $this->argumentResolver);
    }

    public function test_it_returns_arguments_unchanged_if_there_is_no_message_factory_in_the_constructor()
    {
        $arguments = ['foo' => 'bar'];
        $class = new \ReflectionClass(ClassWithNoMessageFactoryArgument::class);

        $resolved = $this->argumentResolver->resolveArguments($class, $arguments);

        $this->assertSame($arguments, $resolved);
    }

    public function test_it_adds_the_message_factory_argument()
    {
        $class = new \ReflectionClass(ClassWithMessageFactoryArgument::class);

        $resolved = $this->argumentResolver->resolveArguments($class, []);

        $this->assertSame([ClassWithMessageFactoryArgument::POSITION => $this->messageFactory], $resolved);
    }
}
