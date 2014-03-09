<?php

namespace Behat\RestExtension\HttpClient;

interface HttpClient
{
    /**
     * @param string $resource
     * @param array  $headers
     *
     * @return Response
     */
    public function get($resource, array $headers = array());

    /**
     * @return Response
     */
    public function getLastResponse();
}