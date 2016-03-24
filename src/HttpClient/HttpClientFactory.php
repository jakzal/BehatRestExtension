<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use Http\Client\HttpClient;

interface HttpClientFactory
{
    /**
     * @return HttpClient
     */
    public function createClient();
}
