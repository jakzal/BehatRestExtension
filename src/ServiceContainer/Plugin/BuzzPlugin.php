<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Http\Adapter\Buzz\Client as BuzzAdapter;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Zalas\Behat\RestExtension\HttpClient\BuzzHttpClientFactory;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

final class BuzzPlugin implements Plugin
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

        if (!class_exists(BuzzAdapter::class)) {
            throw new \RuntimeException('To use the Buzz http client you need to install the "php-http/buzz-adapter" package.');
        }

        $container->register('rest.http_client_factory.buzz', BuzzHttpClientFactory::class)
            ->addArgument(new Reference('rest.message_factory', ContainerInterface::NULL_ON_INVALID_REFERENCE))
            ->addTag('rest.http_client_factory');
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()
            ->arrayNode('buzz')
            ->canBeEnabled();
    }

    /**
     * @param array $config
     *
     * @return bool
     */
    private function isEnabled(array $config)
    {
        return isset($config['buzz']['enabled']) && true === $config['buzz']['enabled'];
    }
}
