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
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class NICK
 * @package WildPHP\Messages
 *
 * Syntax: prefix NICK newnickname
 */
class Nick extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
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
        [$newNickname] = $incomingMessage->getArgs();

        $object = new self($newNickname);
        $object->setPrefix($prefix);
        $object->setNickname($prefix->getNickname());
        $object->setTags($incomingMessage->getTags());

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
