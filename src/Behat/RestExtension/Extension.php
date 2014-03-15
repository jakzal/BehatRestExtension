<?php

namespace Behat\RestExtension;

use Behat\RestExtension\ServiceContainer\HttpClient\BuzzFactory;
use Behat\RestExtension\ServiceContainer\HttpClient\GuzzleFactory;
use Behat\RestExtension\ServiceContainer\HttpClient\HttpClientFactory;
use Behat\Testwork\ServiceContainer\Extension as BehatExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class Extension implements BehatExtension
{
    private $httpClientFactories = array();

    public function __construct()
    {
        $this->registerHttpClientFactory(new BuzzFactory());
        $this->registerHttpClientFactory(new GuzzleFactory());
    }

    public function registerHttpClientFactory(HttpClientFactory $factory)
    {
        $this->httpClientFactories[$factory->getName()] = $factory;
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
        $allowedClients = array_keys($this->httpClientFactories);

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

        foreach ($this->httpClientFactories as $factory) {
            $factoryNode = $httpClientBuilder->children()->arrayNode($factory->getName())->canBeUnset();

            $factory->configure($factoryNode);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $httpClient = $config['http_client'];
        $httpClientName = key($httpClient);

        $httpClientDefinition = $this->httpClientFactories[$httpClientName]->build($httpClient[$httpClientName]);
        $container->setDefinition('behat.rest.http_client.'.$httpClientName, $httpClientDefinition);
        $container->setAlias('behat.rest.http_client', 'behat.rest.http_client.'.$httpClientName);

        $definition = new Definition('Behat\RestExtension\Context\Argument\HttpClientResolver');
        $definition->addArgument(new Reference('behat.rest.http_client'));
        $definition->addTag('context.argument_resolver');
        $container->setDefinition('behat.rest.argument_resolver.http_client', $definition);

        $requestParserDefinition = new Definition('Behat\RestExtension\Message\RequestParser');
        $container->setDefinition('behat.rest.message.request_parser', $requestParserDefinition);

        $definition = new Definition('Behat\RestExtension\Context\Argument\RequestParserResolver');
        $definition->addArgument(new Reference('behat.rest.message.request_parser'));
        $definition->addTag('context.argument_resolver');
        $container->setDefinition('behat.rest.argument_resolver.request_parser', $definition);
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
    }
}
