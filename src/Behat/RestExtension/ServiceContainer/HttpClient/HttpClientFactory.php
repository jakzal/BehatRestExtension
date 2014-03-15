<?php

namespace Behat\RestExtension\ServiceContainer\HttpClient;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;

interface HttpClientFactory
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder);

    /**
     * @param array $config
     *
     * @return Definition
     */
    public function build(array $config);
}