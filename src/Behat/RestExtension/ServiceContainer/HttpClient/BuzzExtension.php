<?php

namespace Behat\RestExtension\ServiceContainer\HttpClient;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class BuzzExtension implements Extension
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'buzz';
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
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../config/http_client'));
        $loader->load('buzz.xml');
    }
}