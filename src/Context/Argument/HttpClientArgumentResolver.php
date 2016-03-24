<?php

namespace Zalas\Behat\RestExtension\Context\Argument;

use Http\Client\HttpClient;
use ReflectionClass;
use Zalas\Behat\RestExtension\HttpClient\HttpClientFactory;

class HttpClientArgumentResolver extends ArgumentResolver
{
    /**
     * @var HttpClientFactory
     */
    private $factory;

    /**
     * @param HttpClientFactory $factory
     */
    public function __construct(HttpClientFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param ReflectionClass $class
     *
     * @return bool
     */
    protected function matches(ReflectionClass $class)
    {
        return HttpClient::class === $class->name;
    }

    /**
     * @return HttpClient
     */
    protected function resolveArgument()
    {
        return $this->factory->createClient();
    }
}
