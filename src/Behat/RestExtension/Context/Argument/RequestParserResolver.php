<?php

namespace Behat\RestExtension\Context\Argument;

use Behat\RestExtension\Message\RequestParser;

class RequestParserResolver extends SimpleArgumentResolver
{
    /**
     * @var RequestParser
     */
    private $requestParser;

    /**
     * @param RequestParser $requestParser
     */
    public function __construct(RequestParser $requestParser)
    {
        $this->requestParser = $requestParser;
    }

    /**
     * @param \ReflectionParameter $argument
     *
     * @return boolean
     */
    protected function supports(\ReflectionParameter $argument)
    {
        if ($argument->getClass()) {
            return get_class($this->requestParser) === $argument->getClass()->getName() || is_subclass_of($this->requestParser, $argument->getClass()->getName());
        }

        return false;
    }

    /**
     * @return mixed
     */
    protected function getArgument()
    {
        return $this->requestParser;
    }
}
