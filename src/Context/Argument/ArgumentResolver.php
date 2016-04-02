<?php

namespace Zalas\Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver as ArgumentResolverInterface;
use ReflectionClass;
use ReflectionMethod;

abstract class ArgumentResolver implements ArgumentResolverInterface
{
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
            if ($parameter->getClass() && $this->matches($parameter->getClass())) {
                $arguments[$position] = $this->resolveArgument();
            }
        }

        return $arguments;
    }

    /**
     * @param ReflectionClass $class
     *
     * @return bool
     */
    abstract protected function matches(ReflectionClass $class);

    /**
     * @return mixed
     */
    abstract protected function resolveArgument();
}
