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
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;

/**
 * Class PONG
 * @package WildPHP\Messages
 *
 * Syntax: PONG server1 [server2]
 */
class Pong extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
{
    protected static $verb = 'PONG';

    protected $server1 = '';

    protected $server2 = '';

    /**
     * PONG constructor.
     *
     * @param string $server1
     * @param string $server2
     */
    public function __construct(string $server1, string $server2 = '')
    {
        $this->setServer1($server1);
        $this->setServer2($server2);
    }

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $args = $incomingMessage->getArgs();
        $server1 = $args[0];
        $server2 = $args[1] ?? '';

        $object = new self($server1, $server2);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return string
     */
    public function getServer1(): string
    {
        return $this->server1;
    }

    /**
     * @param string $server1
     */
    public function setServer1(string $server1)
    {
        $this->server1 = $server1;
    }

    /**
     * @return string
     */
    public function getServer2(): string
    {
        return $this->server2;
    }

    /**
     * @param string $server2
     */
    public function setServer2(string $server2)
    {
        $this->server2 = $server2;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $server2 = $this->getServer2();

        return 'PONG ' . $this->getServer1() . (!empty($server2) ? ' ' . $server2 : '') . "\r\n";
    }
}