<?php

namespace Behat\RestExtension\HttpClient;

use Behat\RestExtension\Message\Request;
use Behat\RestExtension\Message\Response;

interface HttpClient
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function send(Request $request);

    /**
     * @return Response
     */
    public function getLastResponse();
}