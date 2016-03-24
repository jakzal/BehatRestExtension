<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Adapter;

use Http\Discovery\HttpClientDiscovery;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Zalas\Behat\RestExtension\HttpClient\DiscoveryHttpClientFactory;

final class DiscoveryFactory implements AdapterFactory
{
    /**
     * @param array $config
     *
     * @return bool
     */
    public function isEnabled(array $config)
    {
        return class_exists(HttpClientDiscovery::class);
    }

    /**
     * @param array $config
     *
     * @return Definition
     */
    public function buildAdapter(array $config)
    {
        if (!class_exists(HttpClientDiscovery::class)) {
            return;
        }

        return new Definition(DiscoveryHttpClientFactory::class);
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }
}
