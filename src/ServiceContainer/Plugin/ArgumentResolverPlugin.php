<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Zalas\Behat\RestExtension\Context\Argument\HttpClientArgumentResolver;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

final class ArgumentResolverPlugin implements Plugin
{
    /**
     * @param array $config
     *
     * @return bool
     */
    public function isEnabled(array $config)
    {
        return true;
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition(HttpClientArgumentResolver::class, [new Reference('rest.http_client_factory')]);
        $definition->setPublic(false);
        $definition->addTag('context.argument_resolver');
        $container->setDefinition('rest.argument_resolver.http_client', $definition);
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }
}
