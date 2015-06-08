<?php

namespace Zalas\Behat\RestExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use GuzzleHttp\Client as Guzzle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Zalas\Behat\RestExtension\Context\Argument\HttpClientArgumentResolver;
use Zalas\Behat\RestExtension\Http\HttpClient\GuzzleHttpClient;

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
        $builder->children()
            ->arrayNode('guzzle')
            ->prototype('variable');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $guzzleDefinition = new Definition(Guzzle::class, [$config['guzzle']]);
        $httpClientDefinition = new Definition(GuzzleHttpClient::class, [$guzzleDefinition]);
        $container->setDefinition('rest.http_client', $httpClientDefinition);

        $argumentResolverDefinition = new Definition(HttpClientArgumentResolver::class, [new Reference('rest.http_client')]);
        $argumentResolverDefinition->addTag('context.argument_resolver');
        $container->setDefinition('rest.argument_resolver.http_client', $argumentResolverDefinition);
    }
}
