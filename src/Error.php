<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;

use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\MessageTrait;

/**
 * Class Error
 * @package WildPHP\Messages
 *
 * Syntax: ERROR :message
 */
class Error extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use MessageTrait;

    protected static $verb = 'ERROR';

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        [$message] = $incomingMessage->getArgs();

        $object = new self();
        $object->setMessage($message);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }
}