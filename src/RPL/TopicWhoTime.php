<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages\RPL;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;
use WildPHP\Messages\Traits\ServerTrait;

class TopicWhoTime extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ServerTrait;
    use ChannelTrait;
    use PrefixTrait;

    protected static $verb = '333';

    /**
     * @var int
     */
    protected $timestamp = 0;

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

        [$nickname, $channel, $prefix, $timestamp] = $incomingMessage->getArgs();
        $server = $incomingMessage->getPrefix();

        $object = new self();
        $object->setNickname($nickname);
        $object->setServer($server);
        $object->setChannel($channel);
        $object->setPrefix(Prefix::fromString($prefix));
        $object->setTimestamp((int) $timestamp);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }
}
