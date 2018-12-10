<?php
/**
 * Copyright 2018 The WildPHP Team
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
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class Away
 * @package WildPHP\Messages
 *
 * Syntax: prefix AWAY :message
 */
class Away extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
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
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $prefix = Prefix::fromIncomingMessage($incomingMessage);

        $message = $incomingMessage->getArgs()[0];

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