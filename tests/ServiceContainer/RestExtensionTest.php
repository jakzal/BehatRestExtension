<?php

namespace Zalas\Behat\RestExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Configuration\ConfigurationTree;
use Behat\Testwork\ServiceContainer\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zalas\Behat\RestExtension\Context\Argument\HttpClientArgumentResolver;
use Zalas\Behat\RestExtension\HttpClient\DiscoveryHttpClientFactory;
use Zalas\Behat\RestExtension\HttpClient\GuzzleHttpClientFactory;

/**
 * @group integration
 */
class RestExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RestExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->extension = new RestExtension();
    }

    public function test_it_is_a_testwork_extension()
    {
        $this->assertInstanceOf(Extension::class, $this->extension);
    }

    public function test_it_exposes_its_config_key()
    {
        $this->assertSame(RestExtension::CONFIG_KEY, $this->extension->getConfigKey());
    }

    public function test_it_loads_the_guzzle_adapter()
    {
        $container = new ContainerBuilder();

        $this->extension->load($container, $this->processConfiguration(['guzzle' => true]));

        $this->assertHttpClientRegistered($container, GuzzleHttpClientFactory::class);
    }

    public function test_it_loads_the_discovery_adapter()
    {
        $container = new ContainerBuilder();

        $this->extension->load($container, $this->processConfiguration([]));

        $this->assertHttpClientRegistered($container, DiscoveryHttpClientFactory::class);
    }

    public function test_it_loads_the_http_client_argument_resolver()
    {
        $container = new ContainerBuilder();

        $this->extension->load($container, $this->processConfiguration([]));

        $this->assertTrue($container->has('rest.argument_resolver.http_client'));
        $this->assertInstanceOf(HttpClientArgumentResolver::class, $container->get('rest.argument_resolver.http_client'));
    }

    private function assertHttpClientRegistered(ContainerBuilder $container, $class)
    {
        $this->assertTrue($container->has('rest.http_client_factory'));
        $this->assertInstanceOf($class, $container->get('rest.http_client_factory'));
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
