<?php

namespace Zalas\Behat\RestExtension\Context\Argument;

use Http\Client\HttpClient;
use ReflectionClass;

class HttpClientArgumentResolver extends ArgumentResolver
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
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
        return $this->httpClient;
    }
}
