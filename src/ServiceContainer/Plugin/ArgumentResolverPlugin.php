<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Zalas\Behat\RestExtension\Context\Argument\HttpClientArgumentResolver;
use Zalas\Behat\RestExtension\Context\Argument\MessageFactoryArgumentResolver;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

final class ArgumentResolverPlugin implements Plugin
{
    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->registerHttpClientFactoryArgumentResolver($container);
        $this->registerMessageFactoryArgumentResolver($container);
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }

    /**
     * @param ContainerBuilder $container
     */
    private function registerHttpClientFactoryArgumentResolver(ContainerBuilder $container)
    {
        $definition = new Definition(HttpClientArgumentResolver::class, [new Reference('rest.http_client')]);
        $definition->addTag('context.argument_resolver');
        $container->setDefinition('rest.argument_resolver.http_client', $definition);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function registerMessageFactoryArgumentResolver(ContainerBuilder $container)
    {
        $definition = new Definition(MessageFactoryArgumentResolver::class, [new Reference('rest.message_factory')]);
        $definition->addTag('context.argument_resolver');
        $container->setDefinition('rest.argument_resolver.message_factory', $definition);
    }
}
