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
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class ACCOUNT
 * @package WildPHP\Messages
 *
 * Syntax: prefix ACCOUNT accountname
 */
class Account extends BaseIRCMessage implements IncomingMessageInterface
{
    protected static $verb = 'ACCOUNT';

    use PrefixTrait;

    /**
     * @var string
     */
    protected $accountName = '';

    /**
     * ACCOUNT constructor.
     *
     * @param string $accountName
     */
    function __construct(string $accountName)
    {
        $this->setAccountName($accountName);
    }

    /**
     * @param IncomingMessage $incomingMessage
     *
     * @return \self
     * @throws \InvalidArgumentException
     */
    public static function fromIncomingMessage(IncomingMessage $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $accountName = $incomingMessage->getArgs()[0];
        $prefix = Prefix::fromIncomingMessage($incomingMessage);

        $object = new self($accountName);
        $object->setPrefix($prefix);

        return $object;
    }

    /**
     * @return string
     */
    public function getAccountName(): string
    {
        return $this->accountName;
    }

    /**
     * @param string $accountName
     */
    public function setAccountName(string $accountName)
    {
        $this->accountName = $accountName;
    }
}