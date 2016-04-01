<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Http\Discovery\HttpClientDiscovery;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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
        if (!class_exists(HttpClientDiscovery::class)) {
            return;
        }

        $definition = new Definition(DiscoveryHttpClientFactory::class);
        $definition->addTag('rest.http_client_factory');
        $container->setDefinition('rest.http_client_factory.discovery', $definition);
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }
}
