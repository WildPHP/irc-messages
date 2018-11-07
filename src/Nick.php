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
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class NICK
 * @package WildPHP\Messages
 *
 * Syntax: prefix NICK newnickname
 */
class Nick extends BaseIRCMessage implements IncomingMessageInterface, OutgoingMessageInterface
{
    use PrefixTrait;
    use NicknameTrait;

    protected static $verb = 'NICK';

    /**
     * @var string
     */
    protected $newNickname = '';

    /**
     * NICK constructor.
     *
     * @param string $newNickname
     */
    public function __construct(string $newNickname)
    {
        $this->setNewNickname($newNickname);
    }

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

        $prefix = Prefix::fromIncomingMessage($incomingMessage);
        $nickname = $prefix->getNickname();
        $newNickname = $incomingMessage->getArgs()[0];

        $object = new self($newNickname);
        $object->setPrefix($prefix);
        $object->setNickname($nickname);

        return $object;
    }

    /**
     * @return string
     */
    public function getNewNickname(): string
    {
        return $this->newNickname;
    }

    /**
     * @param string $newNickname
     */
    public function setNewNickname(string $newNickname)
    {
        $this->newNickname = $newNickname;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'NICK ' . $this->getNewNickname() . "\r\n";
    }
}