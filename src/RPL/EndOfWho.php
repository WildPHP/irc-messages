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
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\ServerTrait;

class EndOfWho extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ServerTrait;
    use ChannelTrait;
    use MessageTrait;

    protected static $verb = '315';

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

        [$nickname, $channel, $message] = $incomingMessage->getArgs();
        $server = $incomingMessage->getPrefix();

        $object = new self();
        $object->setNickname($nickname);
        $object->setServer($server);
        $object->setChannel($channel);
        $object->setMessage($message);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }
}
