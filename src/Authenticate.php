<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;

use WildPHP\Messages\Generics\BaseIRCMessage;
use WildPHP\Messages\Generics\IncomingMessage;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;

/**
 * Class Authenticate
 * @package WildPHP\Messages
 *
 * Syntax: AUTHENTICATE response
 * @TODO look into the documentation
 */
class Authenticate extends BaseIRCMessage implements IncomingMessageInterface, OutgoingMessageInterface
{
    protected static $verb = 'AUTHENTICATE';

    /**
     * @var string
     */
    protected $response = '';

    /**
     * Authenticate constructor.
     *
     * @param string $response
     */
    public function __construct(string $response)
    {
        $this->setResponse($response);
    }

    /**
     * @param IncomingMessage $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IncomingMessage $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }
        $response = $incomingMessage->getArgs()[0];

        $object = new self($response);

        return $object;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Authenticate ' . $this->getResponse() . "\r\n";
    }
}