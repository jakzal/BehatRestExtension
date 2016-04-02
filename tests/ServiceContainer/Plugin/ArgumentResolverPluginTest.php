<?php

namespace Zalas\Behat\RestExtension\ServiceContainer\Plugin;

use Zalas\Behat\RestExtension\Context\Argument\HttpClientArgumentResolver;
use Zalas\Behat\RestExtension\Context\Argument\MessageFactoryArgumentResolver;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin\Fixtures\DummyHttpClient;
use Zalas\Behat\RestExtension\ServiceContainer\Plugin\Fixtures\DummyMessageFactory;

/**
 * @group integration
 */
class ArgumentResolverPluginTest extends PluginTestCase
{
    /**
     * @return Plugin
     */
    protected function createPlugin()
    {
        return new ArgumentResolverPlugin();
    }

    protected function registerServiceDependencies()
    {
        $this->container->register('rest.http_client', DummyHttpClient::class);
        $this->container->register('rest.message_factory', DummyMessageFactory::class);
    }

    public function test_it_loads_the_http_client_argument_resolver()
    {
        $this->loadPlugin([]);

        $this->assertServiceRegistered('rest.argument_resolver.http_client', HttpClientArgumentResolver::class);
    }

    public function test_it_loads_the_message_factory_argument_resolver()
    {
        $this->loadPlugin([]);

        $this->assertServiceRegistered('rest.argument_resolver.message_factory', MessageFactoryArgumentResolver::class);
    }
}
