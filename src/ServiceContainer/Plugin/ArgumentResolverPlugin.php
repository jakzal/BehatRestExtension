<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $container->register('rest.argument_resolver.http_client', HttpClientArgumentResolver::class)
            ->setPublic(true)
            ->addArgument(new Reference('rest.http_client'))
            ->addTag('context.argument_resolver');
    }

    /**
     * @param ContainerBuilder $container
     */
    private function registerMessageFactoryArgumentResolver(ContainerBuilder $container)
    {
        $container->register('rest.argument_resolver.message_factory', MessageFactoryArgumentResolver::class)
            ->setPublic(true)
            ->addArgument(new Reference('rest.message_factory'))
            ->addTag('context.argument_resolver');
    }
}
