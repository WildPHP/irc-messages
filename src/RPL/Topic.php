<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\RPL;

use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class RPL_TOPIC
 * @package WildPHP\Messages
 *
 * Syntax: :server 332 nickname #channel :topic
 */
class Topic extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ChannelTrait;
    use MessageTrait;
    use ServerTrait;

    protected static $verb = '332';

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $server = $incomingMessage->getPrefix();
        $args = $incomingMessage->getArgs();
        $nickname = array_shift($args);
        $channel = array_shift($args);
        $message = array_shift($args);

        $object = new self();
        $object->setNickname($nickname);
        $object->setChannel($channel);
        $object->setMessage($message);
        $object->setServer($server);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }
}