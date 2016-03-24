<?php

namespace Zalas\Behat\RestExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Zalas\Behat\RestExtension\Context\Argument\HttpClientArgumentResolver;
use Zalas\Behat\RestExtension\HttpClient\DiscoveryHttpClientFactory;
use Zalas\Behat\RestExtension\HttpClient\GuzzleHttpClientFactory;

class RestExtension implements Extension
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'rest';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $this->configureGuzzle($builder);
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $options = is_array($config['guzzle']['config']) ? $config['guzzle']['config'] : [];

        $this->addDiscoveryFactory($container);
        $this->addGuzzleFactory($container, $config);
        $this->addArgumentResolver($container, $options);
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    private function configureGuzzle(ArrayNodeDefinition $builder)
    {
        $guzzle = $builder->children()
            ->arrayNode('guzzle')
            ->canBeEnabled();

        $guzzle->children()
            ->arrayNode('config')
            ->prototype('variable');
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addDiscoveryFactory(ContainerBuilder $container)
    {
        $discoveryFactoryDefinition = new Definition(DiscoveryHttpClientFactory::class);
        $discoveryFactoryDefinition->setPublic(false);
        $container->setDefinition('rest.http_client_factory.discovery', $discoveryFactoryDefinition);
        $container->setAlias('rest.http_client_factory', 'rest.http_client_factory.discovery');
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function addGuzzleFactory(ContainerBuilder $container, array $config)
    {
        if (true === $config['guzzle']['enabled']) {
            $guzzleFactoryDefinition = new Definition(GuzzleHttpClientFactory::class);
            $guzzleFactoryDefinition->setPublic(false);
            $container->setDefinition('rest.http_client_factory.guzzle6', $guzzleFactoryDefinition);
            $container->setAlias('rest.http_client_factory', 'rest.http_client_factory.guzzle6');
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param                  $options
     */
    private function addArgumentResolver(ContainerBuilder $container, $options)
    {
        $argumentResolverDefinition = new Definition(
            HttpClientArgumentResolver::class,
            [new Reference('rest.http_client_factory'), $options]
        );
        $argumentResolverDefinition->addTag('context.argument_resolver');
        $container->setDefinition('rest.argument_resolver.http_client', $argumentResolverDefinition);
    }
}
