<?php

namespace Zalas\Behat\RestExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Http\Client\HttpClient;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin\ArgumentResolverPlugin;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin\DiscoveryPlugin;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin\GuzzleMessageFactoryPlugin;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin\GuzzlePlugin;

class RestExtension implements Extension
{
    const CONFIG_KEY = 'rest';

    /**
     * @var Plugin[]
     */
    private $plugins;

    public function __construct()
    {
        $this->plugins = [
            new GuzzlePlugin(),
            new GuzzleMessageFactoryPlugin(),
            new DiscoveryPlugin(),
            new ArgumentResolverPlugin(),
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
        foreach ($this->plugins as $plugin) {
            $plugin->configure($builder);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        foreach ($this->plugins as $plugin) {
            $plugin->load($container, $config);
        }

        $this->aliasHttpClientFactory($container);
        $this->aliasMessageFactory($container);

        $this->registerHttpClient($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function aliasHttpClientFactory(ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds('rest.http_client_factory');

        if (0 === count($services)) {
            throw new \RuntimeException('No http client adapter is configured. To enable the auto discovery of http clients install the "php-http/discovery" and the "puli/composer-plugin" packages.');
        }

        $container->setAlias('rest.http_client_factory', key($services))->setPublic(true);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function aliasMessageFactory(ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds('rest.message_factory');

        if (0 === count($services)) {
            throw new \RuntimeException('No message factory is configured. Install one of "guzzlehttp/psr7" or "zendframework/zend-diactoros".');
        }

        $container->setAlias('rest.message_factory', key($services))->setPublic(true);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function registerHttpClient(ContainerBuilder $container)
    {
        $definition = new Definition(HttpClient::class);
        $definition->setFactory([new Reference('rest.http_client_factory'), 'createClient']);
        $container->setDefinition('rest.http_client', $definition)->setPublic(true);
    }
}
