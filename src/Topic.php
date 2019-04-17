<?php

/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class TOPIC
 * @package WildPHP\Messages
 *
 * Syntax: prefix TOPIC channel :topic
 */
class Topic extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
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
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() !== self::getVerb()) {
            throw new InvalidArgumentException(sprintf(
                'Expected incoming %s; got %s',
                self::getVerb(),
                $incomingMessage->getVerb()
            ));
        }

        $prefix = Prefix::fromIncomingMessage($incomingMessage);
        $args = $incomingMessage->getArgs();
        $channel = array_shift($args);
        $message = array_shift($args);

        $object = new self($channel, $message);
        $object->setPrefix($prefix);
        $object->setTags($incomingMessage->getTags());

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
