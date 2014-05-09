<?php

namespace spec\Behat\RestExtension\HttpClient;

use Behat\RestExtension\Message\Response;
use PhpSpec\ObjectBehavior;

abstract class HttpClientBehavior extends ObjectBehavior
{
    public function getMatchers()
    {
        return array(
            'beAResponse' => function ($body, $content, $statusCode, $headers) {
                    if (!$body instanceof Response) {
                        return false;
                    }

                    if ($body->getBody() !== $content || $body->getStatusCode() !== $statusCode) {
                        return false;
                    }

                    $headersMatch = true;
                    foreach ($headers as $name => $value) {
                        $headersMatch = $headersMatch && $body->getHeader($name) === $value;
                    }

                    return $headersMatch;
                }
        );
    }
}