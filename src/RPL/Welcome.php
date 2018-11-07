<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\RPL;

use WildPHP\Core\Connection\IncomingIrcMessage;
use WildPHP\Messages\BaseIRCMessage;
use WildPHP\Messages\ReceivableMessage;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class RPL_WELCOME
 * @package WildPHP\Messages
 *
 * Syntax: :server 001 nickname :greeting
 */
class Welcome extends BaseIRCMessage implements ReceivableMessage
{
    use NicknameTrait;
    use ServerTrait;
    use MessageTrait;

    protected static $verb = '001';

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

        $nickname = $incomingIrcMessage->getArgs()[0];
        $message = $incomingIrcMessage->getArgs()[1];
        $server = $incomingIrcMessage->getPrefix();

        $object = new self();
        $object->setNickname($nickname);
        $object->setServer($server);
        $object->setMessage($message);

        return $object;
    }
}