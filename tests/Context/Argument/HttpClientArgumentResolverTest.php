<?php

namespace Zalas\Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zalas\Behat\RestExtension\Context\Argument\Fixtures\ClassWithHttpClientArgument;
use Zalas\Behat\RestExtension\Context\Argument\Fixtures\ClassWithNoConstructor;
use Zalas\Behat\RestExtension\Context\Argument\Fixtures\ClassWithNoHttpClientArgument;

class HttpClientArgumentResolverTest extends TestCase
{
    /**
     * @var HttpClientArgumentResolver
     */
    private $argumentResolver;

    /**
     * @var HttpClient|ObjectProphecy
     */
    private $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = $this->prophesize(HttpClient::class);
        $this->argumentResolver = new HttpClientArgumentResolver($this->httpClient->reveal());
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
        $class = new \ReflectionClass(ClassWithHttpClientArgument::class);

        $resolved = $this->argumentResolver->resolveArguments($class, []);

        $this->assertSame([ClassWithHttpClientArgument::POSITION => $this->httpClient->reveal()], $resolved);
    }
}
