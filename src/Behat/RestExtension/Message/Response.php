<?php

namespace Behat\RestExtension\Message;

class Response
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var integer
     */
    private $statusCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * @param string  $body
     * @param integer $statusCode
     * @param array   $headers
     */
    public function __construct($body, $statusCode, array $headers = array())
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
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
}
