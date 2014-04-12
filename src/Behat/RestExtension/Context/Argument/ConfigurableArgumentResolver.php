<?php

namespace Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;

/**
 * Resolves arguments based on a list like:
 *  *  HttpClient => $httpClient
 *  *  MyService => $myService
 */
class ConfigurableArgumentResolver implements ArgumentResolver
{
    /**
     * @var array
     */
    private $supportedArguments;

    /**
     * @param array $supportedArguments
     */
    public function __construct(array $supportedArguments)
    {
        $this->supportedArguments = $supportedArguments;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveArguments(\ReflectionClass $classReflection, array $arguments)
    {
        if ($constructor = $classReflection->getConstructor()) {
            $arguments = $this->resolveConstructorArguments($constructor, $arguments);
        }

        return $arguments;
    }

    /**
     * @param \ReflectionMethod $constructor
     * @param array             $arguments
     *
     * @return array
     */
    private function resolveConstructorArguments(\ReflectionMethod $constructor, array $arguments)
    {
        $constructorParameters = $constructor->getParameters();
        foreach ($constructorParameters as $position => $parameter) {
            foreach ($this->supportedArguments as $classOrInterface => $service) {
                if ($parameter->getClass() && $this->supports($parameter->getClass(), $classOrInterface, $service)) {
                    $arguments[$position] = $service;
                }

            }
        }

        return $arguments;
    }

    /**
     * @param tring  $parameterClass
     * @param string $classOrInterface
     * @param mixed  $service
     *
     * @return boolean
     */
    private function supports(\ReflectionClass $parameterClass, $classOrInterface, $service)
    {
        return (interface_exists($classOrInterface) && $parameterClass->implementsInterface($classOrInterface))
            || $parameterClass->getName() === $classOrInterface
            || is_subclass_of($service, $parameterClass->getName());
    }
}
