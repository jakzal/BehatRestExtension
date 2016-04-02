<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use GuzzleHttp\Psr7\Request;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

final class GuzzleMessageFactoryPlugin implements Plugin
{
    /**
     * @param ContainerBuilder $container
     * @param array            $config
     *
     * @return Definition
     */
    public function load(ContainerBuilder $container, array $config)
    {
        if (!class_exists(Request::class)) {
            return;
        }

        $definition = new Definition(GuzzleMessageFactory::class);
        $definition->addTag('rest.message_factory');
        $container->setDefinition('rest.message_factory.guzzle', $definition);
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }
}
