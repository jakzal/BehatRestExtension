<?php

namespace Zalas\Behat\RestExtension\Context\Argument\Fixtures;

use Http\Client\HttpClient;

final class ClassWithHttpClientArgument
{
    /**
     * Position of the http client argument (starting from 0).
     */
    const POSITION = 2;

    public function __construct($foo, $bar, HttpClient $httpClient)
    {
    }
}
