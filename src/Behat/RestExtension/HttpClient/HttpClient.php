<?php

namespace Behat\RestExtension\HttpClient;

use Behat\RestExtension\Message\Response;

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
     * @param string $resource
     * @param array  $headers
     *
     * @return Response
     */
    public function head($resource, array $headers = array());

    /**
     * @param string $resource
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function post($resource, array $headers = array(), $content = null);

    /**
     * @param string $resource
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function put($resource, array $headers = array(), $content = null);

    /**
     * @param string $resource
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function patch($resource, array $headers = array(), $content = null);

    /**
     * @param string $resource
     * @param array  $headers
     * @param string $content
     *
     * @return Response
     */
    public function delete($resource, array $headers = array(), $content = null);

//    public function options();

    /**
     * @return Response
     */
    public function getLastResponse();
}