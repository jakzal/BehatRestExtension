<?php

namespace Behat\RestExtension;

use Behat\RestExtension\ServiceContainer\HttpClient\BuzzExtension;
use Behat\RestExtension\ServiceContainer\HttpClient\GuzzleExtension;
use Behat\RestExtension\ServiceContainer\HttpClient\Extension as HttpClientExtension;
use Behat\Testwork\ServiceContainer\Extension as BehatExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class Extension implements BehatExtension
{
    /**
     * @var HttpClientExtension[]
     */
    private $httpClientExtensions = array();

    public function __construct()
    {
        $this->registerHttpClientExtension(new BuzzExtension());
        $this->registerHttpClientExtension(new GuzzleExtension());
    }

    /**
     * @param HttpClientExtension $httpClientExtension
     */
    public function registerHttpClientExtension(HttpClientExtension $httpClientExtension)
    {
        $this->httpClientExtensions[$httpClientExtension->getName()] = $httpClientExtension;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigKey()
    {
        return 'rest';
    }

    /**
     * {@inheritDoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $allowedClients = array_keys($this->httpClientExtensions);

        $builder
            ->beforeNormalization()
                ->always()
                ->then(
                    function ($v) use ($allowedClients) {
                        if (!is_array($v)) {
                            return $v;
                        }

                        foreach ($allowedClients as $clientName) {
                            if (!array_key_exists($clientName, $v) || isset($v['http_client'][$clientName])) {
                                continue;
                            }

                            $v['http_client'][$clientName] = $v[$clientName];

                            unset($v[$clientName]);
                        }

                        return $v;
                    })
            ->end();

        $httpClientBuilder = $builder->children()->arrayNode('http_client');
        $httpClientBuilder
            ->isRequired()
            ->validate()
                ->ifTrue(function ($v) { return count($v) !== 1; } )
                ->then(function ($v) {
                    throw new \InvalidArgumentException(sprintf('There can only be a single http client configured, but found %d: "%s"', count($v), implode(', ', array_keys($v))));
                })
            ->end();

        $differ = $builder->children()->scalarNode('differ');
        $differ->defaultValue('coduo');

        foreach ($this->httpClientExtensions as $extension) {
            $extensionNode = $httpClientBuilder->children()->arrayNode($extension->getName())->canBeUnset();

            $extension->configure($extensionNode);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/ServiceContainer/config'));
        $loader->load('services.xml');
        $loader->load('differs.xml');

        $container->setAlias('behat.rest.differ', 'behat.rest.differ.'.$config['differ']);

        $this->loadHttpClientDefinition($container, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     *
     * @throws \RuntimeException
     */
    private function loadHttpClientDefinition(ContainerBuilder $container, array $config)
    {
        $httpClient = $config['http_client'];
        $httpClientName = key($httpClient);
        $httpClientServiceId = 'behat.rest.http_client.'.$httpClientName;

        $this->httpClientExtensions[$httpClientName]->load($container, $httpClient[$httpClientName]);

        if (!$container->hasDefinition($httpClientServiceId)) {
            throw new \RuntimeException(sprintf('Expected the %s http client extension to register a "%s" service', $httpClientName, $httpClientServiceId));
        }

        $container->setAlias('behat.rest.http_client', $httpClientServiceId);
    }
}
