<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;

use WildPHP\Core\Connection\IncomingIrcMessage;
use WildPHP\Messages\Traits\MessageTrait;

/**
 * Class Error
 * @package WildPHP\Messages
 *
 * Syntax: ERROR :message
 */
class Error extends BaseIRCMessage implements ReceivableMessage
{
    use MessageTrait;

    protected static $verb = 'ERROR';

    /**
     * @param IncomingIrcMessage $incomingIrcMessage
     *
     * @return \self
     * @throws \InvalidArgumentException
     */
    public static function fromIncomingIrcMessage(IncomingIrcMessage $incomingIrcMessage): self
    {
        if ($incomingIrcMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingIrcMessage->getVerb());
        }

        $message = $incomingIrcMessage->getArgs()[0];
        $object = new self();
        $object->setMessage($message);

        return $object;
    }
}