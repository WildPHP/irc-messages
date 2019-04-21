<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class Chghost
 * @package WildPHP\Messages
 *
 * Syntax: prefix CHGHOST newUsername newHostname
 */
class Chghost extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use PrefixTrait;

    protected static $verb = 'CHGHOST';

    /**
     * @var string
     */
    protected $newUsername;

    /**
     * @var string
     */
    protected $newHostname;

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return mixed
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage)
    {
        if ($incomingMessage->getVerb() !== self::getVerb()) {
            throw new InvalidArgumentException(sprintf(
                'Expected incoming %s; got %s',
                self::getVerb(),
                $incomingMessage->getVerb()
            ));
        }

        $prefix = Prefix::fromIncomingMessage($incomingMessage);
        [$newUsername, $newHostname] = $incomingMessage->getArgs();

        $object = new self();
        $object->setPrefix($prefix);
        $object->setNewUsername($newUsername);
        $object->setNewHostname($newHostname);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return string
     */
    public function getNewUsername(): string
    {
        return $this->newUsername;
    }

    /**
     * @param string $newUsername
     */
    public function setNewUsername(string $newUsername): void
    {
        $this->newUsername = $newUsername;
    }

    /**
     * @return string
     */
    public function getNewHostname(): string
    {
        return $this->newHostname;
    }

    /**
     * @param string $newHostname
     */
    public function setNewHostname(string $newHostname): void
    {
        $this->newHostname = $newHostname;
    }
}
