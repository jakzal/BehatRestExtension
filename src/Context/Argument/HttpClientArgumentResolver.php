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
     * @var array
     */
    private $options;

    /**
     * @param HttpClientFactory $factory
     * @param array             $options
     */
    public function __construct(HttpClientFactory $factory, array $options = [])
    {
        $this->factory = $factory;
        $this->options = $options;
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
        return $this->factory->createClient($this->options);
    }
}
