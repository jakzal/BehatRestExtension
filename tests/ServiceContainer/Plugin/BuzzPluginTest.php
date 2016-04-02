<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Zalas\Behat\RestExtension\HttpClient\BuzzHttpClientFactory;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

/**
 * @group integration
 */
class BuzzPluginTest extends PluginTestCase
{
    /**
     * @return Plugin
     */
    protected function createPlugin()
    {
        return new BuzzPlugin();
    }

    public function test_it_loads_the_buzz_http_client_factory()
    {
        $this->loadPlugin(['buzz' => true]);

        $this->assertServiceRegistered('rest.http_client_factory.buzz', BuzzHttpClientFactory::class);
    }

    public function test_it_is_disabled_by_default()
    {
        $this->loadPlugin([]);

        $this->assertServiceNotRegistered('rest.http_client_factory.buzz');
    }
}
