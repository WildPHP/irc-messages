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
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class PRIVMSG
 * @package WildPHP\Messages
 *
 * Syntax: prefix PRIVMSG #channel :message
 */
class Privmsg extends BaseIRCMessage implements IncomingMessageInterface, OutgoingMessageInterface
{
    use PrefixTrait;
    use ChannelTrait;
    use NicknameTrait;
    use MessageTrait;

    protected static $verb = 'PRIVMSG';

    /**
     * @var bool|string
     */
    protected $ctcpVerb = false;

    /**
     * @var bool
     */
    protected $isCtcp = false;

    /**
     * PRIVMSG constructor.
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
     * @param IncomingMessage $incomingMessage
     *
     * @return \self
     */
    public static function fromIncomingMessage(IncomingMessage $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $prefix = Prefix::fromIncomingMessage($incomingMessage);
        $channel = $incomingMessage->getArgs()[0];
        $message = $incomingMessage->getArgs()[1];

        $isCtcp = substr($message, 0, 1) == "\x01" && substr($message, -1, 1) == "\x01";
        $ctcpVerb = false;

        if ($isCtcp) {
            $message = trim(substr($message, 1, -1));
            $message = explode(' ', $message, 2);
            $ctcpVerb = array_shift($message);
            $message = !empty($message) ? array_shift($message) : '';
        }

        $object = new self($channel, $message);
        $object->setPrefix($prefix);
        $object->setIsCtcp($isCtcp);
        $object->setCtcpVerb($ctcpVerb);
        $object->setNickname($prefix->getNickname());

        return $object;
    }

    /**
     * @return bool|string
     */
    public function getCtcpVerb()
    {
        return $this->ctcpVerb;
    }

    /**
     * @param bool|string $ctcpVerb
     */
    public function setCtcpVerb($ctcpVerb)
    {
        $this->ctcpVerb = $ctcpVerb;
    }

    /**
     * @return bool
     */
    public function isCtcp(): bool
    {
        return $this->isCtcp;
    }

    /**
     * @param bool $isCtcp
     */
    public function setIsCtcp(bool $isCtcp)
    {
        $this->isCtcp = $isCtcp;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->isCtcp()) {
            $message = "\x01" . $this->getCtcpVerb() . ' ' . $this->getMessage() . "\x01";
        } else {
            $message = $this->getMessage();
        }

        return 'PRIVMSG ' . $this->getChannel() . ' :' . $message . "\r\n";
    }
}