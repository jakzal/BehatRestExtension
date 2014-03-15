<?php


namespace Behat\RestExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;

abstract class SimpleArgumentResolver implements ArgumentResolver
{
    /**
     * @param \ReflectionClass $classReflection
     * @param mixed[]         $arguments
     *
     * @return mixed[]
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
    protected function resolveConstructorArguments(\ReflectionMethod $constructor, array $arguments)
    {
        foreach ($constructor->getParameters() as $position => $parameter) {
            if ($this->supports($parameter)) {
                $arguments[$position] = $this->getArgument();
            }
        }

        return $arguments;
    }

    /**
     * @param \ReflectionParameter $argument
     *
     * @return boolean
     */
    abstract protected function supports(\ReflectionParameter $argument);

    /**
     * @return mixed
     */
    abstract protected function getArgument();
}