<?php

namespace Zalas\Behat\RestExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Configuration\ConfigurationTree;
use Behat\Testwork\ServiceContainer\Extension;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Adapter\Buzz\Client as BuzzAdapter;
use Http\Message\MessageFactory;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zalas\Behat\RestExtension\Context\Argument\HttpClientArgumentResolver;
use Zalas\Behat\RestExtension\Context\Argument\MessageFactoryArgumentResolver;

/**
 * @group integration
 */
class RestExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RestExtension
     */
    private $extension;

    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->extension = new RestExtension();
        $this->container = new ContainerBuilder();
    }

    public function test_it_is_a_testwork_extension()
    {
        $this->assertInstanceOf(Extension::class, $this->extension);
    }

    public function test_it_exposes_its_config_key()
    {
        $this->assertSame(RestExtension::CONFIG_KEY, $this->extension->getConfigKey());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No http client adapter is configured.
     */
    public function test_it_throws_an_exception_if_no_http_client_is_configured()
    {
        $this->loadExtension(['discovery' => false]);
    }

    public function test_it_loads_the_guzzle_adapter()
    {
        $this->loadExtension(['guzzle' => true, 'discovery' => false]);

        $this->assertHttpClientRegistered(GuzzleAdapter::class);
    }

    public function test_it_loads_the_discovery_adapter()
    {
        $this->loadExtension([]);

        $this->assertHttpClientRegistered(BuzzAdapter::class);
    }

    public function test_it_loads_the_buzz_adapter()
    {
        $this->loadExtension(['buzz' => true, 'discovery' => false]);

        $this->assertHttpClientRegistered(BuzzAdapter::class);
    }

    public function test_it_loads_the_http_client_argument_resolver()
    {
        $this->loadExtension($this->processConfiguration([]));

        $this->assertTrue($this->container->has('rest.argument_resolver.http_client'));
        $this->assertInstanceOf(HttpClientArgumentResolver::class, $this->container->get('rest.argument_resolver.http_client'));
    }

    public function test_it_loads_the_message_factory_argument_resolver()
    {
        $this->loadExtension([]);

        $this->assertTrue($this->container->has('rest.argument_resolver.message_factory'));
        $this->assertInstanceOf(MessageFactoryArgumentResolver::class, $this->container->get('rest.argument_resolver.message_factory'));
    }

    public function test_it_loads_the_message_factory()
    {
        $this->loadExtension([]);

        $this->assertTrue($this->container->has('rest.message_factory'));
        $this->assertInstanceOf(MessageFactory::class, $this->container->get('rest.message_factory'));
    }

    private function assertHttpClientRegistered($class)
    {
        $this->assertTrue($this->container->has('rest.http_client'));
        $this->assertInstanceOf($class, $this->container->get('rest.http_client'));
    }

    private function loadExtension(array $config = [])
    {
        $this->extension->load($this->container, $this->processConfiguration($config));
        $this->container->compile();
    }

    private function processConfiguration(array $config = [])
    {
        $configurationTree = new ConfigurationTree();
        $configTree = $configurationTree->getConfigTree([$this->extension]);

        $key = $this->extension->getConfigKey();
        $config = (new Processor())->process($configTree, ['testwork' => [$key => $config]]);

        return $config[$key];
    }
}
