<?php

/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;


use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class KICK
 * @package WildPHP\Messages
 *
 * Syntax: prefix KICK #channel nickname :message
 */
class Kick extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
{
    use ChannelTrait;
    use PrefixTrait;
    use NicknameTrait;
    use MessageTrait;

    protected static $verb = 'KICK';

    /**
     * @var string
     */
    protected $target = '';

    /**
     * KICK constructor.
     *
     * @param string $channel
     * @param string $nickname
     * @param string $message
     */
    public function __construct(string $channel, string $nickname, string $message)
    {
        $this->setChannel($channel);
        $this->setTarget($nickname);
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
            throw new InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $prefix = Prefix::fromIncomingMessage($incomingMessage);
        [$channel, $target, $message] = $incomingMessage->getArgs();

        $object = new self($channel, $target, $message);
        $object->setPrefix($prefix);
        $object->setNickname($prefix->getNickname());
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'KICK ' . $this->getChannel() . ' ' . $this->getTarget() . ' :' . $this->getMessage() . "\r\n";
    }
}