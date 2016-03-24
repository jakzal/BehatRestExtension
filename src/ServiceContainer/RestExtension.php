<?php

namespace Zalas\Behat\RestExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Zalas\Behat\RestExtension\Context\Argument\HttpClientArgumentResolver;
use Zalas\Behat\RestExtension\ServiceContainer\Adapter\AdapterFactory;
use Zalas\Behat\RestExtension\ServiceContainer\Adapter\DiscoveryFactory;
use Zalas\Behat\RestExtension\ServiceContainer\Adapter\GuzzleFactory;

class RestExtension implements Extension
{
    const CONFIG_KEY = 'rest';

    /**
     * @var AdapterFactory[]
     */
    private $factories;

    public function __construct()
    {
        $this->factories = [
            new GuzzleFactory(),
            new DiscoveryFactory(),
        ];
    }

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
        return self::CONFIG_KEY;
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
        foreach ($this->factories as $factory) {
            $factory->configure($builder);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadFactory($container, $config);
        $this->addArgumentResolver($container);

        if (!$container->hasDefinition('rest.http_client_factory')) {
            throw new \RuntimeException('No http client adapter is configured. To enable the auto discovery of http clients install the "php-http/discovery" and the "puli/composer-plugin" packages.');
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function loadFactory(ContainerBuilder $container, array $config)
    {
        foreach ($this->factories as $factory) {
            if ($factory->isEnabled($config)) {
                $container->setDefinition('rest.http_client_factory', $factory->buildAdapter($config));

                return;
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param                  $options
     */
    private function addArgumentResolver(ContainerBuilder $container)
    {
        $definition = new Definition(HttpClientArgumentResolver::class, [new Reference('rest.http_client_factory')]);
        $definition->addTag('context.argument_resolver');
        $container->setDefinition('rest.argument_resolver.http_client', $definition);
    }
}
