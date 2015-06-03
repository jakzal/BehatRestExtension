<?php

namespace Zalas\Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use ReflectionClass;
use ReflectionMethod;
use Zalas\Behat\RestExtension\Http\HttpClient;
use Zend\Diactoros\Request;

class HttpClientArgumentResolver implements ArgumentResolver
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
     * {@inheritdoc}
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        if ($constructor = $classReflection->getConstructor()) {
            $arguments = $this->resolveConstructorArguments($constructor, $arguments);
        }

        return $arguments;
    }

    /**
     * @param ReflectionMethod $constructor
     * @param array            $arguments
     *
     * @return array
     */
    private function resolveConstructorArguments(ReflectionMethod $constructor, array $arguments)
    {
        $constructorParameters = $constructor->getParameters();

        foreach ($constructorParameters as $position => $parameter) {
            if ($parameter->getClass() && HttpClient::class === $parameter->getClass()->getName()) {
                $arguments[$position] = $this->httpClient;
            }
        }

        return $arguments;
    }
}
