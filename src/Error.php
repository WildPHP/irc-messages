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
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Traits\MessageTrait;

/**
 * Class Error
 * @package WildPHP\Messages
 *
 * Syntax: ERROR :message
 */
class Error extends BaseIRCMessage implements IncomingMessageInterface
{
    use MessageTrait;

    protected static $verb = 'ERROR';

    /**
     * @param IncomingMessage $incomingMessage
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromIncomingMessage(IncomingMessage $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $message = $incomingMessage->getArgs()[0];
        $object = new self();
        $object->setMessage($message);

        return $object;
    }
}