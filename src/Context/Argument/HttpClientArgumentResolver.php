<?php

namespace Zalas\Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use Http\Client\HttpClient;
use ReflectionClass;
use ReflectionMethod;
use Zalas\Behat\RestExtension\HttpClient\HttpClientFactory;

class HttpClientArgumentResolver implements ArgumentResolver
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
     * {@inheritdoc}
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        if ($constructor = $classReflection->getConstructor()) {
            return $this->resolveConstructorArguments($constructor, $arguments);
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
            if ($parameter->getClass() && HttpClient::class === $parameter->getClass()->name) {
                $arguments[$position] = $this->factory->createClient($this->options);
            }
        }

        return $arguments;
    }
}
