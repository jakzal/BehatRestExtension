<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Adapter;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;

interface AdapterFactory
{
    /**
     * @param array $config
     *
     * @return bool
     */
    public function isEnabled(array $config);

    /**
     * @param array $config
     *
     * @return Definition
     */
    public function buildAdapter(array $config);

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder);
}
