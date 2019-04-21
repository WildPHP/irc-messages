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
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class Invite
 * @package WildPHP\Messages
 *
 * Syntax: prefix INVITE target #channel
 */
class Invite extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use PrefixTrait;
    use NicknameTrait;
    use ChannelTrait;

    protected static $verb = 'INVITE';

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     * @throws InvalidArgumentException
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
        [$target, $channel] = $incomingMessage->getArgs();

        $object = new self();
        $object->setPrefix($prefix);
        $object->setNickname($target);
        $object->setChannel($channel);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }
}
