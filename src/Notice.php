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
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class NOTICE
 * @package WildPHP\Messages
 *
 * Syntax: prefix NOTICE #channel :message
 */
class Notice extends BaseIRCMessage implements ReceivableMessage, SendableMessage
{
    use PrefixTrait;
    use ChannelTrait;
    use NicknameTrait;
    use MessageTrait;

    /**
     * @var string
     */
    protected static $verb = 'NOTICE';

    /**
     * NOTICE constructor.
     *
     * @param string $channel
     * @param string $message
     */
    public function __construct(string $channel, string $message)
    {
        $this->setChannel($channel);
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
        $channel = $incomingIrcMessage->getArgs()[0];
        $message = $incomingIrcMessage->getArgs()[1];

        $object = new self($channel, $message);
        $object->setPrefix($prefix);
        $object->setNickname($prefix->getNickname());

        return $object;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'NOTICE ' . $this->getChannel() . ' :' . $this->getMessage() . "\r\n";
    }
}