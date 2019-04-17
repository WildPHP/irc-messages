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
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class ACCOUNT
 * @package WildPHP\Messages
 *
 * Syntax: prefix ACCOUNT accountname
 */
class Account extends BaseIRCMessageImplementation implements IncomingMessageInterface
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
    public function __construct(string $accountName)
    {
        $this->setAccountName($accountName);
    }

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() !== self::getVerb()) {
            throw new InvalidArgumentException(sprintf('Expected incoming %s; got %s', self::getVerb(), $incomingMessage->getVerb()));
        }

        [$accountName] = $incomingMessage->getArgs();
        $prefix = Prefix::fromIncomingMessage($incomingMessage);

        $object = new self($accountName);
        $object->setPrefix($prefix);
        $object->setTags($incomingMessage->getTags());

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
