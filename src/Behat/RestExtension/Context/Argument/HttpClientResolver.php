<?php

namespace Behat\RestExtension\Context\Argument;

use Behat\RestExtension\HttpClient\HttpClient;

class HttpClientResolver extends SimpleArgumentResolver
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
     * @return mixed
     */
    protected function getArgument()
    {
        return $this->httpClient;
    }

    /**
     * @param \ReflectionParameter $argument
     *
     * @return boolean
     */
    protected function supports(\ReflectionParameter $argument)
    {
        return $argument->getClass() && $argument->getClass()->implementsInterface('Behat\RestExtension\HttpClient\HttpClient');
    }
}
