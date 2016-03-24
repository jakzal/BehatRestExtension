<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use Http\Client\HttpClient;

interface HttpClientFactory
{
    /**
     * @param array $config
     *
     * @return HttpClient
     */
    public function createClient(array $config = []);
}
