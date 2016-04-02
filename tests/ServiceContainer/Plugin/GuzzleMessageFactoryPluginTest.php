<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;

/**
 * @group integration
 */
class GuzzleMessageFactoryPluginTest extends PluginTestCase
{
    /**
     * @return Plugin
     */
    protected function createPlugin()
    {
        return new GuzzleMessageFactoryPlugin();
    }

    public function test_it_registers_the_guzzle_message_factory()
    {
        $this->loadPlugin([]);

        $this->assertServiceRegistered('rest.message_factory.guzzle', GuzzleMessageFactory::class);
    }
}
