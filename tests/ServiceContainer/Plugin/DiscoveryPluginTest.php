<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Zalas\Behat\RestExtension\HttpClient\DiscoveryHttpClientFactory;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

/**
 * @group integration
 */
class DiscoveryPluginTest extends PluginTestCase
{
    /**
     * @return Plugin
     */
    protected function createPlugin()
    {
        return new DiscoveryPlugin();
    }

    public function test_it_loads_the_discovery_http_client_factory_by_default()
    {
        $this->loadPlugin([]);

        $this->assertServiceRegistered('rest.http_client_factory.discovery', DiscoveryHttpClientFactory::class);
    }

    public function test_it_can_be_disabled()
    {
        $this->loadPlugin(['discovery' => false]);

        $this->assertServiceNotRegistered('rest.http_client_factory.discovery');
    }
}
