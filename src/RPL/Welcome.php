<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\RPL;

use WildPHP\Messages\Generics\BaseIRCMessage;
use WildPHP\Messages\Generics\IncomingMessage;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class RPL_WELCOME
 * @package WildPHP\Messages
 *
 * Syntax: :server 001 nickname :greeting
 */
class Welcome extends BaseIRCMessage implements IncomingMessageInterface
{
    use NicknameTrait;
    use ServerTrait;
    use MessageTrait;

    protected static $verb = '001';

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

        $nickname = $incomingMessage->getArgs()[0];
        $message = $incomingMessage->getArgs()[1];
        $server = $incomingMessage->getPrefix();

        $object = new self();
        $object->setNickname($nickname);
        $object->setServer($server);
        $object->setMessage($message);

        return $object;
    }
}