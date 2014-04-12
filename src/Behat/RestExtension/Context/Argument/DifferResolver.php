<?php

namespace Behat\RestExtension\Context\Argument;

use Behat\RestExtension\Differ\Differ;

class DifferResolver extends SimpleArgumentResolver
{
    /**
     * @var Differ
     */
    private $differ;

    /**
     * @param Differ $differ
     */
    public function __construct(Differ $differ)
    {
        $this->differ = $differ;
    }

    /**
     * @param \ReflectionParameter $argument
     *
     * @return boolean
     */
    protected function supports(\ReflectionParameter $argument)
    {
        return $argument->getClass() && $argument->getClass()->implementsInterface('Behat\RestExtension\Differ\Differ');
    }

    /**
     * @return mixed
     */
    protected function getArgument()
    {
        return $this->differ;
    }
}
