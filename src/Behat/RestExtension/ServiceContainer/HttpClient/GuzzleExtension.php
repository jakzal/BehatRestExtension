<?php

namespace Behat\RestExtension\ServiceContainer\HttpClient;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GuzzleExtension implements Extension
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
    public function load(ContainerBuilder $container, array $config)
    {
    }
}