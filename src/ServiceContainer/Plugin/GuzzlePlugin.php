<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Zalas\Behat\RestExtension\HttpClient\GuzzleHttpClientFactory;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

final class GuzzlePlugin implements Plugin
{
    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        if (!$this->isEnabled($config)) {
            return;
        }

        if (!class_exists(GuzzleAdapter::class)) {
            throw new \RuntimeException('To use the Guzzle http client you need to install the "php-http/guzzle6-adapter" package.');
        }

        $definition = new Definition(GuzzleHttpClientFactory::class, [$config['guzzle']['config']]);
        $definition->addTag('rest.http_client_factory');
        $container->setDefinition('rest.http_client_factory.guzzle', $definition);
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

    /**
     * @param array $config
     *
     * @return bool
     */
    private function isEnabled(array $config)
    {
        return isset($config['guzzle']['enabled']) && true === $config['guzzle']['enabled'];
    }
}
