<?php

namespace Zalas\Behat\RestExtension\HttpClient;

use Buzz\Client\FileGetContents;
use Http\Client\HttpClient;
use Http\Adapter\Buzz\Client as Adapter;
use Http\Message\MessageFactory;

final class BuzzHttpClientFactory implements HttpClientFactory
{
    /**
     * @var MessageFactory|null
     */
    private $messageFactory;

    public function __construct(MessageFactory $messageFactory = null)
    {
        $this->messageFactory = $messageFactory;
    }

    /**
     * @return HttpClient
     */
    public function createClient()
    {
        return new Adapter(new FileGetContents(), $this->messageFactory);
    }
}
