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
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class TOPIC
 * @package WildPHP\Messages
 *
 * Syntax: prefix TOPIC channel :topic
 */
class Topic extends BaseIRCMessage implements ReceivableMessage, SendableMessage
{
    protected static $verb = 'TOPIC';

    use MessageTrait;
    use ChannelTrait;
    use PrefixTrait;

    /**
     * @param string $channelName
     * @param string $message
     */
    public function __construct(string $channelName, string $message)
    {
        $this->setChannel($channelName);
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
        $args = $incomingIrcMessage->getArgs();
        $channel = array_shift($args);
        $message = array_shift($args);

        $object = new self($channel, $message);
        $object->setPrefix($prefix);

        return $object;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'TOPIC ' . $this->getChannel() . ' :' . $this->getMessage() . "\r\n";
    }
}