<?php

namespace Behat\RestExtension\Message;

class Request
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $resource;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @param string $method
     * @param string $resource
     */
    public function __construct($method, $resource)
    {
        $this->method = $method;
        $this->resource = $resource;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * @param string      $name
     * @param string|null $default
     *
     * @return string|null
     */
    public function getHeader($name, $default = null)
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : $default;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
