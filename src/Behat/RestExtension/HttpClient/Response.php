<?php

namespace Behat\RestExtension\HttpClient;

class Response
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var integer
     */
    private $statusCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * @param string  $content
     * @param integer $statusCode
     * @param array   $headers
     */
    public function __construct($content, $statusCode, array $headers = array())
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
