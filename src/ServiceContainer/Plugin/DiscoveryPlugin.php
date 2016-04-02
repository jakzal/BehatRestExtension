<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Http\Discovery\HttpClientDiscovery;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zalas\Behat\RestExtension\HttpClient\DiscoveryHttpClientFactory;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

final class DiscoveryPlugin implements Plugin
{
    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        if (!class_exists(HttpClientDiscovery::class) || false === $config['discovery']['enabled']) {
            return;
        }

        $container->register('rest.http_client_factory.discovery', DiscoveryHttpClientFactory::class)
            ->addTag('rest.http_client_factory');
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()
            ->arrayNode('discovery')
            ->canBeDisabled();
    }
}
