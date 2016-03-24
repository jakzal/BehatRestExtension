<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Adapter;

use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Zalas\Behat\RestExtension\HttpClient\GuzzleHttpClientFactory;

final class GuzzleFactory implements AdapterFactory
{
    /**
     * @param array $config
     *
     * @return bool
     */
    public function isEnabled(array $config)
    {
        return true === $config['guzzle']['enabled'];
    }

    /**
     * @param array $config
     *
     * @return Definition
     */
    public function buildAdapter(array $config)
    {
        if (!class_exists(GuzzleAdapter::class)) {
            throw new \RuntimeException('To use the Guzzle http client you need to install the "php-http/guzzle6-adapter" package.');
        }

        return new Definition(GuzzleHttpClientFactory::class, [$config['guzzle']['config']]);
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $guzzle = $builder->children()
            ->arrayNode('guzzle')
            ->canBeEnabled();

        $guzzle->children()
            ->arrayNode('config')
            ->prototype('variable');
    }
}
