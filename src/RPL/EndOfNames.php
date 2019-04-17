<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\RPL;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;

/**
 * Class RPL_ENDOFNAMES
 * @package WildPHP\Messages
 *
 * Syntax: :server 366 nickname #channel :End of /NAMES list.
 */
class EndOfNames extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ChannelTrait;
    use MessageTrait;

    protected static $verb = '366';

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

        [$nickname, $channel, $message] = $incomingMessage->getArgs();

        $object = new self();
        $object->setNickname($nickname);
        $object->setChannel($channel);
        $object->setMessage($message);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }
}
