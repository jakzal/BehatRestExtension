<?php

namespace Behat\RestExtension\ServiceContainer\HttpClient;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;

class BuzzFactory implements HttpClientFactory
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'buzz';
    }

    /**
     * {@inheritDoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function build(array $config)
    {
        if (!class_exists('Buzz\Browser')) {
            throw new \RuntimeException('Install kriswallsmith/buzz in order to use the buzz browser.');
        }

        $buzzDefinition = new Definition('Buzz\Browser');

        $definition = new Definition('Behat\RestExtension\HttpClient\BuzzHttpClient');
        $definition->addArgument($buzzDefinition);

        return $definition;
    }
}