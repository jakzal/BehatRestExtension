<?php

namespace Behat\RestExtension\Message;

class RequestParser
{
    /**
     * @param string  $message
     * @param Request $request
     */
    public function parse($message, Request $request)
    {
        $this->addHeaders($message, $request);
        $this->setBody($message, $request);
    }

    /**
     * @param string  $message
     * @param Request $request
     */
    private function addHeaders($message, Request $request)
    {
        if (!$this->hasHeaders($message)) {
            return;
        }

        $messageParts = preg_split('/^$/m', $message);
        $headers = array_filter(explode("\n", $messageParts[0]));

        foreach ($headers as $header) {
            list($name, $value) = preg_split('/\s*:\s*/', $header);
            $request->addHeader($name, $value);
        }
    }

    /**
     * @param string $message
     *
     * @return boolean
     */
    private function hasHeaders($message)
    {
        $firstLine = strtok($message, "\n");

        return 1 === preg_match('/^[^()<>@,;:\\"\/\[\]{}]+:[^:]+/i', $firstLine);
    }

    /**
     * @param         $message
     * @param Request $request
     */
    private function setBody($message, Request $request)
    {
        if (!$this->hasBody($message)) {
            return;
        }

        $messageParts = preg_split('/^$/m', $message);
        $body = 2 === count($messageParts) ? $messageParts[1] : $messageParts[0];
        $request->setBody(trim($body));
    }

    /**
     * @param string $message
     *
     * @return boolean
     */
    private function hasBody($message)
    {
        return 1 === preg_match('/^$/m', $message) || !$this->hasHeaders($message);
    }
}
