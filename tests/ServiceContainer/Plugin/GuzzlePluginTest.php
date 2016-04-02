<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Zalas\Behat\RestExtension\HttpClient\GuzzleHttpClientFactory;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

/**
 * @group integration
 */
class GuzzlePluginTest extends PluginTestCase
{
    /**
     * @return Plugin
     */
    protected function createPlugin()
    {
        return new GuzzlePlugin();
    }

    public function test_it_loads_the_guzzle_http_client_factory()
    {
        $this->loadPlugin(['guzzle' => true]);

        $this->assertServiceRegistered('rest.http_client_factory.guzzle', GuzzleHttpClientFactory::class);
    }

    public function test_it_is_disabled_by_default()
    {
        $this->loadPlugin([]);

        $this->assertServiceNotRegistered('rest.http_client_factory.guzzle');
    }
}
