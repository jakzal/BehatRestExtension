<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

abstract class PluginTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Plugin
     */
    protected $plugin;

    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @before
     */
    public function initialize()
    {
        $this->container = new ContainerBuilder();
        $this->plugin = $this->createPlugin();

        $this->registerServiceDependencies();
    }

    protected function registerServiceDependencies()
    {
    }

    /**
     * @return Plugin
     */
    abstract protected function createPlugin();

    public function test_it_is_a_plugin()
    {
        $this->assertInstanceOf(Plugin::class, $this->plugin);
    }

    /**
     * @param array $config
     */
    protected function loadPlugin(array $config = [])
    {
        $config = $this->processConfig($config);
        $this->plugin->load($this->container, $config);
        $this->container->compile();
    }

    /**
     * @param array $config
     */
    protected function processConfig(array $config)
    {
        $tree = new TreeBuilder();

        $this->plugin->configure($tree->root('rest_test'));

        return (new Processor())->process($tree->buildTree(), ['rest_test' => $config]);
    }

    protected function assertServiceRegistered($id, $class)
    {
        $this->assertTrue($this->container->has($id));
        $this->assertInstanceOf($class, $this->container->get($id));
    }

    protected function assertServiceNotRegistered($id)
    {
        $this->assertFalse($this->container->has($id));
    }
}
