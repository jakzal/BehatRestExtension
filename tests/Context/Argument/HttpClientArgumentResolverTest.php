<?php

namespace Zalas\Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use Http\Client\HttpClient;
use Prophecy\Prophecy\ObjectProphecy;
use Zalas\Behat\RestExtension\Context\Argument\Fixtures\ClassWithHttpClientArgument;
use Zalas\Behat\RestExtension\Context\Argument\Fixtures\ClassWithNoConstructor;
use Zalas\Behat\RestExtension\Context\Argument\Fixtures\ClassWithNoHttpClientArgument;
use Zalas\Behat\RestExtension\HttpClient\HttpClientFactory;

class HttpClientArgumentResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClientArgumentResolver
     */
    private $argumentResolver;

    /**
     * @var HttpClientFactory|ObjectProphecy
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = $this->prophesize(HttpClientFactory::class);
        $this->argumentResolver = new HttpClientArgumentResolver($this->factory->reveal());
    }

    public function test_it_is_an_argument_resolver()
    {
        $this->assertInstanceOf(ArgumentResolver::class, $this->argumentResolver);
    }

    public function test_it_returns_arguments_unchanged_if_there_is_no_constructor()
    {
        $arguments = ['foo' => 'bar'];
        $class = new \ReflectionClass(ClassWithNoConstructor::class);

        $resolved = $this->argumentResolver->resolveArguments($class, $arguments);

        $this->assertSame($arguments, $resolved);
    }

    public function test_it_returns_arguments_unchanged_if_no_http_client_is_present()
    {
        $arguments = ['foo' => 'bar'];
        $class = new \ReflectionClass(ClassWithNoHttpClientArgument::class);

        $resolved = $this->argumentResolver->resolveArguments($class, $arguments);

        $this->assertSame($arguments, $resolved);
    }

    public function test_it_adds_the_http_client_argument()
    {
        $httpClient = $this->prophesize(HttpClient::class)->reveal();
        $this->factory->createClient()->willReturn($httpClient);
        $class = new \ReflectionClass(ClassWithHttpClientArgument::class);

        $resolved = $this->argumentResolver->resolveArguments($class, []);

        $this->assertSame([ClassWithHttpClientArgument::POSITION => $httpClient], $resolved);
    }
}
