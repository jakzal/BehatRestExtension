<?php

namespace Behat\RestExtension\ServiceContainer\HttpClient;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class GuzzleFactory implements HttpClientFactory
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'guzzle';
    }

    /**
     * {@inheritDoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function build(array $config)
    {
    }
}