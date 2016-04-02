<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use GuzzleHttp\Psr7\Request;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

final class GuzzleMessageFactoryPlugin implements Plugin
{
    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        if (!class_exists(Request::class)) {
            return;
        }

        $container->register('rest.message_factory.guzzle', GuzzleMessageFactory::class)
            ->addTag('rest.message_factory');
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }
}
