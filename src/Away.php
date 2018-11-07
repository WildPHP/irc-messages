<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;

use WildPHP\Core\Connection\IncomingIrcMessage;
use WildPHP\Core\Connection\UserPrefix;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class Away
 * @package WildPHP\Messages
 *
 * Syntax: prefix AWAY :message
 */
class Away extends BaseIRCMessage implements ReceivableMessage, SendableMessage
{
    use PrefixTrait;
    use MessageTrait;
    use NicknameTrait;

    protected static $verb = 'AWAY';

    /**
     * Away constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->setMessage($message);
    }

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

        $prefix = UserPrefix::fromIncomingIrcMessage($incomingIrcMessage);

        $message = $incomingIrcMessage->getArgs()[0];

        $object = new self($message);
        $object->setPrefix($prefix);
        $object->setNickname($prefix->getNickname());

        return $object;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Away :' . $this->getMessage() . "\r\n";
    }
}