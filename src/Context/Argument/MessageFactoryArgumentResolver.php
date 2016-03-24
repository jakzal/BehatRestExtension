<?php

namespace Zalas\Behat\RestExtension\Context\Argument;

use Http\Message\MessageFactory;
use ReflectionClass;

final class MessageFactoryArgumentResolver extends ArgumentResolver
{
    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @param MessageFactory $messageFactory
     */
    public function __construct(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;
    }

    /**
     * @param ReflectionClass $class
     *
     * @return bool
     */
    protected function matches(ReflectionClass $class)
    {
        return MessageFactory::class === $class->name;
    }

    /**
     * @return mixed
     */
    protected function resolveArgument()
    {
        return $this->messageFactory;
    }
}
